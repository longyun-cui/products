<?php
namespace App\Repositories\Admin;

use App\Models\People;
use App\Models\Product;
use App\Models\Event;

use App\Repositories\Common\CommonRepository;

use Response, Auth, Validator, DB, Exception;
use QrCode;

class EventRepository {

    private $model;
    public function __construct()
    {
//        $this->model = new Product;
    }

    // 返回列表数据
    public function get_list_datatable($post_data)
    {
        $admin = Auth::guard("admin")->user();
        $query = Event::select("*")->with(['admin']);
        if(!empty($post_data['name'])) $query->where('name', 'like', "%{$post_data['name']}%");
        if(!empty($post_data['title'])) $query->where('title', 'like', "%{$post_data['title']}%");
        if(!empty($post_data['category'])) $query->where('category', 'like', "%{$post_data['category']}%");
        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 20;

        if(isset($post_data['order']))
        {
            $columns = $post_data['columns'];
            $order = $post_data['order'][0];
            $order_column = $order['column'];
            $order_dir = $order['dir'];

            $field = $columns[$order_column]["data"];
            if($field == "start_time") $query->orderByRaw(DB::raw('cast(start_time as SIGNED) '.$order_dir));
            if($field == "end_time") $query->orderByRaw(DB::raw('cast(end_time as SIGNED) '.$order_dir));
            $query->orderBy($field, $order_dir);
        }
        else $query->orderBy("updated_at", "desc");

        if($limit == -1) $list = $query->get();
        else $list = $query->skip($skip)->take($limit)->get();

        foreach ($list as $k => $v)
        {
            $list[$k]->encode_id = encode($v->id);
        }
        return datatable_response($list, $draw, $total);
    }

    // 返回添加视图
    public function view_create()
    {
        return view('admin.event.edit');
    }
    // 返回编辑视图
    public function view_edit()
    {
        $id = request("id",0);
        $decode_id = decode($id);
        if(!$decode_id) return response("参数有误", 404);

        if($decode_id == 0)
        {
            return view('admin.event.edit')->with(['operate'=>'create', 'encode_id'=>$id]);
        }
        else
        {
            $data = Product::with(['people'])->find($decode_id);
            if($data)
            {
                unset($data->id);
                return view('admin.event.edit')->with(['operate'=>'edit', 'encode_id'=>$id, 'data'=>$data]);
            }
            else return response("该事件不存在！", 404);
        }
    }

    // 保存数据
    public function save($post_data)
    {
        $messages = [
            'id.required' => '参数有误',
//            'name.required' => '请输入后台名称',
            'title.required' => '请输入作品标题',
//            'start_time.required' => '请输入开始时间',
//            'end_time.required' => '请输入结束时间',
        ];
        $v = Validator::make($post_data, [
            'id' => 'required',
//            'name' => 'required',
            'title' => 'required'//,
//            'start_time' => 'required'
//            'end_time' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $admin = Auth::guard('admin')->user();

        $id = decode($post_data["id"]);
        $operate = $post_data["operate"];
        if(intval($id) !== 0 && !$id) return response_error();

        DB::beginTransaction();
        try
        {
            if($operate == 'create') // $id==0，添加一个新的作者
            {
                $event = new Event;
                $post_data["admin_id"] = $admin->id;
            }
            elseif('edit') // 编辑
            {
                $event = Event::find($id);
                if(!$event) return response_error([],"该作者不存在，刷新页面重试");
                if($event->admin_id != $admin->id) return response_error([],"你没有操作权限");
            }
            else throw new Exception("operate--error");


            if(!empty($post_data['start_time'])) $post_data['start_time'] = trim($post_data['start_time']);
            if(!empty($post_data['end_time'])) $post_data['end_time'] = trim($post_data['end_time']);
            $bool = $event->fill($post_data)->save();
            if($bool)
            {
                $encode_id = encode($event->id);

                if(!empty($post_data["cover"]))
                {
                    $upload = new CommonRepository();
                    $result = $upload->upload($post_data["cover"], 'unique-cover-events' , 'cover_event'.$encode_id);
                    if($result["status"])
                    {
                        $event->cover_pic = $result["data"];
                        $event->save();
                    }
                    else throw new Exception("upload--cover--fail");
                }
            }
            else throw new Exception("insert--event--fail");


            DB::commit();
            return response_success(['id'=>$encode_id]);
        }
        catch (Exception $e)
        {
            DB::rollback();
//            exit($e->getMessage());
//            $msg = $e->getMessage();
            $msg = '操作失败，请重试！';
            return response_fail([], $msg);
        }
    }

    // 删除
    public function delete($post_data)
    {
        $admin = Auth::guard('admin')->user();
        $id = decode($post_data["id"]);
        if(intval($id) !== 0 && !$id) return response_error([],"该事件不存在，刷新页面试试");

        $event = Event::find($id);
        if($event->admin_id != $admin->id) return response_error([],"你没有操作权限");

        DB::beginTransaction();
        try
        {
            $bool = $event->delete();
            if(!$bool) throw new Exception("delete--event--fail");

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            return response_fail([],'删除失败，请重试');
        }

    }

    // 启用
    public function enable($post_data)
    {
        $admin = Auth::guard('admin')->user();
        $id = decode($post_data["id"]);
        if(intval($id) !== 0 && !$id) return response_error([],"该事件不存在，刷新页面试试");

        $event = Event::find($id);
        if($event->admin_id != $admin->id) return response_error([],"你没有操作权限");
        $update["active"] = 1;
        DB::beginTransaction();
        try
        {
            $bool = $event->fill($update)->save();
            if(!$bool) throw new Exception("update--event--fail");

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            return response_fail([],'启用失败，请重试');
        }
    }

    // 禁用
    public function disable($post_data)
    {
        $admin = Auth::guard('admin')->user();
        $id = decode($post_data["id"]);
        if(intval($id) !== 0 && !$id) return response_error([],"该事件不存在，刷新页面试试");

        $event = Event::find($id);
        if($event->admin_id != $admin->id) return response_error([],"你没有操作权限");
        $update["active"] = 9;
        DB::beginTransaction();
        try
        {
            $bool = $event->fill($update)->save();
            if(!$bool) throw new Exception("update--event--fail");

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            return response_fail([],'禁用失败，请重试');
        }
    }



    //
    public function select2_peoples($post_data)
    {
        if(empty($post_data['keyword']))
        {
            $list =People::select(['id','name as text'])->orderBy('id','desc')->get()->toArray();
        }
        else
        {
            $keyword = "%{$post_data['keyword']}%";
            $list =People::select(['id','name as text'])->where('name','like',"%$keyword%")->orderBy('id','desc')->get()->toArray();
        }
        return $list;
    }

    //
    public function select2_products($post_data)
    {
        if(empty($post_data['keyword']))
        {
            $list =Product::select(['id','title as text'])->orderBy('id','desc')->get()->toArray();
        }
        else
        {
            $keyword = "%{$post_data['keyword']}%";
            $list =Product::select(['id','title as text'])->where('title','like',"%$keyword%")->orderBy('id','desc')->get()->toArray();
        }
        return $list;
    }


}