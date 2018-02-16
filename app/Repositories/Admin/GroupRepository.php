<?php
namespace App\Repositories\Admin;

use App\Models\People;
use App\Models\Product;
use App\Models\Group;

use App\Repositories\Common\CommonRepository;

use Response, Auth, Validator, DB, Exception;
use QrCode;

class GroupRepository {

    private $model;
    public function __construct()
    {
//        $this->model = new Group;
    }

    public function index()
    {
        return view('admin.index');
    }

    // 返回列表数据
    public function get_list_datatable($post_data)
    {
        $admin = Auth::guard("admin")->user();
        $query = Group::select("*")->with(['admin']);
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
        return view('admin.group.edit');
    }
    // 返回编辑视图
    public function view_edit()
    {
        $id = request("id",0);
        $decode_id = decode($id);
        if(!$decode_id) return response("参数有误", 404);

        if($decode_id == 0)
        {
            return view('admin.group.edit')->with(['operate'=>'create', 'encode_id'=>$id]);
        }
        else
        {
            $data = Group::find($decode_id);
            if($data)
            {
                unset($data->id);
                return view('admin.group.edit')->with(['operate'=>'edit', 'encode_id'=>$id, 'data'=>$data]);
            }
            else return response("该作者不存在！", 404);
        }
    }

    // 保存数据
    public function save($post_data)
    {
        $messages = [
            'id.required' => '参数有误',
            'title.required' => '请输入组名称',
        ];
        $v = Validator::make($post_data, [
            'id' => 'required',
            'title' => 'required'
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
                $group = new Group;
                $post_data["admin_id"] = $admin->id;
            }
            elseif('edit') // 编辑
            {
                $group = Group::find($id);
                if(!$group) return response_error([],"该作者不存在，刷新页面重试");
                if($group->admin_id != $admin->id) return response_error([],"你没有操作权限");
            }
            else throw new Exception("operate--error");

            $bool = $group->fill($post_data)->save();
            if($bool)
            {
                $encode_id = encode($group->id);

                if(!empty($post_data["cover"]))
                {
                    $upload = new CommonRepository();
                    $result = $upload->upload($post_data["cover"], 'unique-cover-groups' , 'cover_group_'.$encode_id);
                    if($result["status"])
                    {
                        $group->cover_pic = $result["data"];
                        $group->save();
                    }
                    else throw new Exception("upload--cover--fail");
                }
            }
            else throw new Exception("insert--group--fail");


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

        $group = Group::find($id);
        if($group->admin_id != $admin->id) return response_error([],"你没有操作权限");

        DB::beginTransaction();
        try
        {
            $bool = $group->delete();
            if(!$bool) throw new Exception("delete--group--fail");

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
        if(intval($id) !== 0 && !$id) return response_error([],"该组不存在，刷新页面试试");

        $group = Group::find($id);
        if($group->admin_id != $admin->id) return response_error([],"你没有操作权限");
        $update["active"] = 1;
        DB::beginTransaction();
        try
        {
            $bool = $group->fill($update)->save();
            if(!$bool) throw new Exception("update--group--fail");

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
        if(intval($id) !== 0 && !$id) return response_error([],"该组不存在，刷新页面试试");

        $group = Group::find($id);
        if($group->admin_id != $admin->id) return response_error([],"你没有操作权限");
        $update["active"] = 9;
        DB::beginTransaction();
        try
        {
            $bool = $group->fill($update)->save();
            if(!$bool) throw new Exception("update--group--fail");

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
            $list =Product::select(['id','name as text'])->orderBy('id','desc')->get()->toArray();
        }
        else
        {
            $keyword = "%{$post_data['keyword']}%";
            $list =Product::select(['id','name as text'])->where('name','like',"%$keyword%")->orderBy('id','desc')->get()->toArray();
        }
        return $list;
    }





}