<?php
namespace App\Repositories\Admin;

use App\Models\People;
use App\Models\Product;

use App\Repositories\Common\CommonRepository;

use Response, Auth, Validator, DB, Exception;
use QrCode;

class PeopleRepository {

    private $model;
    public function __construct()
    {
//        $this->model = new People;
    }

    public function index()
    {
        return view('admin.index');
    }

    // 返回列表数据
    public function get_list_datatable($post_data)
    {
        $admin = Auth::guard("admin")->user();
        $query = People::select("*")->with(['admin']);
        if(!empty($post_data['name'])) $query->where('name', 'like', "%{$post_data['name']}%");
        if(!empty($post_data['major'])) $query->where('major', 'like', "%{$post_data['major']}%");
        if(!empty($post_data['nation'])) $query->where('nation', 'like', "%{$post_data['nation']}%");
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
        return view('admin.people.edit');
    }
    // 返回编辑视图
    public function view_edit()
    {
        $id = request("id",0);
        $decode_id = decode($id);
        if(!$decode_id) return response("参数有误", 404);

        if($decode_id == 0)
        {
            return view('admin.people.edit')->with(['operate'=>'create', 'encode_id'=>$id]);
        }
        else
        {
            $data = People::find($decode_id);
            if($data)
            {
                unset($data->id);
                return view('admin.people.edit')->with(['operate'=>'edit', 'encode_id'=>$id, 'data'=>$data]);
            }
            else return response("该作者不存在！", 404);
        }
    }

    // 保存数据
    public function save($post_data)
    {
        $messages = [
            'id.required' => '参数有误',
            'name.required' => '请输入作者姓名',
        ];
        $v = Validator::make($post_data, [
            'id' => 'required',
            'name' => 'required'
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
                $people = new People;
                $post_data["admin_id"] = $admin->id;
            }
            elseif('edit') // 编辑
            {
                $people = People::find($id);
                if(!$people) return response_error([],"该作者不存在，刷新页面重试");
                if($people->admin_id != $admin->id) return response_error([],"你没有操作权限");
            }
            else throw new Exception("operate--error");

            $bool = $people->fill($post_data)->save();
            if($bool)
            {
                $encode_id = encode($people->id);

                if(!empty($post_data["cover"]))
                {
                    $upload = new CommonRepository();
                    $result = $upload->upload($post_data["cover"], 'unique-cover-peoples' , 'cover_people_'.$encode_id);
                    if($result["status"])
                    {
                        $people->cover_pic = $result["data"];
                        $people->save();
                    }
                    else throw new Exception("upload--cover--fail");
                }
            }
            else throw new Exception("insert--people--fail");


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
        if(intval($id) !== 0 && !$id) return response_error([],"该作者不存在，刷新页面试试");

        $people = People::find($id);
        if($people->admin_id != $admin->id) return response_error([],"你没有操作权限");

        DB::beginTransaction();
        try
        {
            $bool = $people->delete();
            if(!$bool) throw new Exception("delete--people--fail");
            $people->products()->detach();

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
        if(intval($id) !== 0 && !$id) return response_error([],"该作者不存在，刷新页面试试");

        $people = People::find($id);
        if($people->admin_id != $admin->id) return response_error([],"你没有操作权限");
        $update["active"] = 1;
        DB::beginTransaction();
        try
        {
            $bool = $people->fill($update)->save();
            if(!$bool) throw new Exception("update--people--fail");

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
        if(intval($id) !== 0 && !$id) return response_error([],"该文章不存在，刷新页面试试");

        $people = People::find($id);
        if($people->admin_id != $admin->id) return response_error([],"你没有操作权限");
        $update["active"] = 9;
        DB::beginTransaction();
        try
        {
            $bool = $people->fill($update)->save();
            if(!$bool) throw new Exception("update--people--fail");

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            return response_fail([],'禁用失败，请重试');
        }
    }




    // 返回列表数据
    public function view_people_product($post_data)
    {
        $people_encode = $post_data['id'];
        $people_decode = decode($people_encode);
        if(!$people_decode && intval($people_decode) !== 0) return view('admin.404');

        $people = People::find($people_decode);
        if($people)
        {
            $people->encode = encode($people->id);
            unset($people->id);

            if(request()->isMethod('get'))
            {
                return view('admin.people.product')->with(['people'=>$people]);
            }
            else if(request()->isMethod('post')) return $this->get_people_product_list_datatable($post_data);
        }
        else return view('admin.404');

    }
    // 返回列表数据
    public function get_people_product_list_datatable($post_data)
    {
        $admin = Auth::guard("admin")->user();
        $people_encode = $post_data['id'];
        $people_decode = decode($people_encode);
        $query = Product::select("*")->with(['admin','people'])->where('people_id', $people_decode);
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
            $query->orderBy($field, $order_dir);
        }
        else $query->orderBy("updated_at", "desc");

        if($limit == -1) $list = $query->get();
        else $list = $query->skip($skip)->take($limit)->get();

        foreach ($list as $k => $v)
        {
            $list[$k]->encode_id = encode($v->id);
            if(!empty($list[$k]->people)) $list[$k]->people->encode_id = encode($v->people->id);
        }
        return datatable_response($list, $draw, $total);
    }


}