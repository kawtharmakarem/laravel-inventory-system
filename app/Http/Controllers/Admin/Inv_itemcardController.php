<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inv_itemcardRequest;
use App\Models\Admin;
use App\Models\Inv_Itemcard;
use App\Models\Inv_Itemcard_Categories;
use App\Models\Inv_Itemcard_Movement_Category;
use App\Models\Inv_Itemcard_Movement_Type;
use App\Models\Inv_Uom;
use App\Models\Store;
use Illuminate\Http\Request;

class Inv_itemcardController extends Controller
{
    public function index()
    {
        $com_code=auth()->user()->com_code;
      
        $data=get_cols_where_p(new Inv_Itemcard(),array("*"),array("com_code" => $com_code),'id','DESC',PAGINATION_COUNT);
        if(!empty($data))
        {
            foreach ($data as $info) {
                $info->added_by_admin=get_field_value(new Admin(),'name',array('id'=>$info->added_by));
                $info->inv_itemcard_categories_name=get_field_value(new Inv_Itemcard_Categories(),'name',array('id'=>$info->inv_itemcard_categories_id));
                // $info->parent_item_name=get_field_value(new Inv_Itemcard(),'name',array('id'=>$info->parent_inv_itemcard_id));
                $info->uom_name=get_field_value(new Inv_Uom(),'name',array('id'=>$info->uom_id));
                $info->retail_uom_name=get_field_value(new Inv_Uom(),'name',array('id'=>$info->retail_uom_id));
                if($info->updated_by>0 and $info->updated_by!=null)
                {
                    $info->updated_by_admin=get_field_value(new Admin(),'name',array('id'=>$info->updated_by));
                    
                }

            }
        }
        $inv_itemcard_categories=get_cols_where(new Inv_Itemcard_Categories(),array('id','name'),array('com_code'=>$com_code,'active'=>1),'id','DESC');

        return view('admin.inv_itemcard.index',['data'=>$data,'inv_itemcard_categories'=>$inv_itemcard_categories]);

    }
    public function create()
    {
        $com_code=auth()->user()->com_code;
        $inv_itemcard_categories=get_cols_where(new Inv_Itemcard_Categories(),array('id','name'),array('com_code'=>$com_code,'active'=>1),'id','DESC');
       $inv_uoms_parent=get_cols_where(new Inv_Uom(),array('id','name'),array('com_code'=>$com_code,'active'=>1,'is_master'=>1),'id','DESC');
       $inv_uoms_child=get_cols_where(new Inv_Uom(),array('id','name'),array('com_code'=>$com_code,'active'=>1,'is_master'=>0),'id','DESC');
       $item_card_data=get_cols_where(new Inv_Itemcard(),array('id','name'),array('com_code'=>$com_code,'active'=>1),'id','DESC'); 
       return view('admin.inv_itemcard.create',['inv_itemcard_categories'=>$inv_itemcard_categories,'inv_uoms_parent'=>$inv_uoms_parent,'inv_uoms_child'=>$inv_uoms_child,'item_card_data'=>$item_card_data]);
    }
    public function store(Inv_itemcardRequest $request)
    {
        try{
     $com_code=auth()->user()->com_code;
     //set item_code for itemcard
     $row = get_cols_where_row_orderby(new Inv_Itemcard(), array("item_code"), array("com_code" => $com_code), 'id', 'DESC');
     if(!empty($row)){
        $data_insert['item_code']=$row['item_code']+1;
     }else{
        $data_insert['item_code']=1;
     }
     //check if not exists for barcode
     if($request->barcode!='')
     {
        $checkExists_barcode=Inv_Itemcard::where(['barcode'=>$request->barcode,'com_code'=>$com_code])->first();
        if(!empty($checkExists_barcode)){
            return redirect()->back()->with(['error'=>'This code is registered before .'])->withInput();
        }else{
            $data_insert['barcode']=$request->barcode;
        }
     
     }else{
        $data_insert['barcode']="item".$data_insert['item_code'];
     }
     //check for name
     $checkExists_name=Inv_Itemcard::where(['name'=>$request->name,'com_code'=>$com_code])->first();
     if(!empty($checkExists_name))
     {
        return redirect()->back()->with(['error'=>'Sorry ! category name is already existed .'])->withInput();
     }
     $data_insert['name']=$request->name;
     $data_insert['item_type']=$request->item_type;
     $data_insert['inv_itemcard_categories_id']=$request->inv_itemcard_categories_id;
     $data_insert['uom_id']=$request->uom_id;
     $data_insert['price']=$request->price;
     $data_insert['nos_gomla_price']=$request->nos_gomla_price;
     $data_insert['gomla_price']=$request->gomla_price;
     $data_insert['cost_price']=$request->cost_price;
     $data_insert['does_has_retailunit']=$request->does_has_retailunit;
     $data_insert['parent_inv_itemcard_id']=$request->parent_inv_itemcard_id;
     if($data_insert['parent_inv_itemcard_id']==""){
        $data_insert['parent_inv_itemcard_id']=0;
     }
    if($data_insert['does_has_retailunit']==1){
        $data_insert['retail_uom_quntToParent']=$request->retail_uom_quntToParent;
        $data_insert['retail_uom_id']=$request->retail_uom_id;
        $data_insert['price_retail']=$request->price_retail;
       $data_insert['nos_gomla_price_retail']=$request->nos_gomla_price_retail;
       $data_insert['gomla_price_retail']=$request->gomla_price_retail;
        $data_insert['cost_price_retail']=$request->cost_price_retail;
    }
    if($request->has('item_img')){
        $request->validate([
            'item_img'=>'required|mimes:png,jpg,jpeg|max:2000'
        ]);
        $the_file_path=uploadImage('assets/admin/uploads',$request->item_img);
        $data_insert['photo']=$the_file_path;
    }
    $data_insert['has_fixed_price']=$request->has_fixed_price;
    $data_insert['active']=$request->active;
    $data_insert['added_by']=auth()->user()->id;
    $data_insert['created_at']=date("Y-m-d H:i:s");
    $data_insert['date']=date("Y-m-d");
    $data_insert['com_code']=$com_code;
    Inv_Itemcard::create($data_insert);
    return redirect()->route('admin.inv_itemcard.index')->with(['success'=>'Data added successfully .']);
      }catch(\Exception $ex){
    return redirect()->back()->with(['error'=>'Something went wrong ..'.$ex->getMessage()])->withInput();
        }

    }
    public function edit($id)
    {
        $data=get_cols_where_row(new Inv_Itemcard(),array("*"),array('id'=>$id));
        $com_code=auth()->user()->com_code;
        $inv_itemcard_categories=get_cols_where(new Inv_Itemcard_Categories(),array('id','name'),array('com_code'=>$com_code,'active'=>1),'id','DESC');
        $inv_uoms_parent=get_cols_where(new Inv_Uom(),array('id','name'),array('com_code'=>$com_code,'active'=>1,'is_master'=>1),'id','DESC');
        $inv_uoms_child=get_cols_where(new Inv_Uom(),array('id','name'),array('com_code'=>$com_code,'active'=>1,'is_master'=>0),'id','DESC');
        
        return view('admin.inv_itemcard.edit',['data'=>$data,'inv_itemcard_categories'=>$inv_itemcard_categories,'inv_uoms_parent'=>$inv_uoms_parent,'inv_uoms_child'=>$inv_uoms_child]);
    }
    

    public function update($id,Inv_itemcardRequest $request)
    {
        try
        {
            $com_code=auth()->user()->com_code;
            $data=get_cols_where_row(new Inv_Itemcard(),array("*"),array('id'=>$id));
            if(empty($data))
            {
                return redirect()->route('admin.inv_itemcard.index')->with(['error'=>'Unable to find required data'])->withInput();
            }
            
            
              //check if not exists for barcode
              if($request->barcode!=''){
                $checkExists_barcode = Inv_Itemcard::where(['barcode' => $request->barcode, 'com_code' => $com_code])->where("id","!=",$id)->first();
                if (!empty($checkExists_barcode)) {
                    return redirect()->back()
                    ->with(['error' => 'عفوا باركود الصنف مسجل من قبل'])
                    ->withInput();
        
                }else{
                    $data_insert['barcode']=$request->barcode;     
                }
            }
        
            //check if not exists for name
            $checkExists_name=Inv_Itemcard::where(['name'=>$request->name,'com_code'=>$com_code])->where('id','!=',$id)->first();
            if(!empty($checkExists_name))
            {
                return redirect()->back()->with(['error'=>'Sorry ! This CategoryName is already existed .'])->withInput();
            }
            $data_to_update['name']=$request->name;
            $data_to_update['item_type']=$request->item_type;
            $data_to_update['inv_itemcard_categories_id']=$request->inv_itemcard_categories_id;
            $data_to_update['uom_id']=$request->uom_id;
            $data_to_update['price']=$request->price;
            $data_to_update['nos_gomla_price']=$request->nos_gomla_price;
            $data_to_update['gomla_price']=$request->gomla_price;
            $data_to_update['cost_price']=$request->cost_price;
            $data_to_update['does_has_retailunit']=$request->does_has_retailunit;
            $data_to_update['parent_inv_itemcard_id']=$request->parent_inv_itemcard_id;
            if($data_to_update['parent_inv_itemcard_id']==""){
                $data_to_update['parent_inv_itemcard_id']=0;  
            }
           if($data_to_update['does_has_retailunit']==1){ 
            $data_to_update['retail_uom_quntToParent']=$request->retail_uom_quntToParent;
            $data_to_update['retail_uom_id']=$request->retail_uom_id;
            $data_to_update['price_retail']=$request->price_retail;
            $data_to_update['nos_gomla_price_retail']=$request->nos_gomla_price_retail;
            $data_to_update['gomla_price_retail']=$request->gomla_price_retail;
            $data_to_update['cost_price_retail']=$request->cost_price_retail;
        
           }
        
           if ($request->has('photo')) {
            $request->validate([
                'photo' => 'required|mimes:png,jpg,jpeg|max:2000',
            ]);
            $oldphotoPath = $data['photo'];
            $the_file_path = uploadImage('assets/admin/uploads', $request->photo);
            if (file_exists('assets/admin/uploads/' . $oldphotoPath) and !empty($oldphotoPath)) {
                unlink('assets/admin/uploads/' . $oldphotoPath);
            }

            $data_to_update['photo'] = $the_file_path;
        }
            $data_to_update['has_fixed_price']=$request->has_fixed_price;
            $data_to_update['active']=$request->active;
           $data_to_update['updated_by'] = auth()->user()->id;
          $data_to_update['updated_at'] = date("Y-m-d H:i:s");
          update(new Inv_ItemCard(),$data_to_update,array('id' => $id, 'com_code' => $com_code));
                     return redirect()->route('admin.inv_itemcard.index')->with(['success' => 'Data Updated successfully..']);
                 } catch (\Exception $ex) {
         
                     return redirect()->back()
                         ->with(['error' => 'Sorry ! something went wrong' . $ex->getMessage()])
                         ->withInput();
                 }
    }


    public function delete($id)
    {
        try
        {
            $com_code=auth()->user()->com_code;
            $item_row=get_cols_where_row(new Inv_Itemcard(),array("id"),array('id'=>$id,'com_code'=>$com_code));
            if(!empty($item_row))
            {
                $flag=delete(new Inv_Itemcard(),array('id'=>$id,'com_code'=>$com_code));
                if($flag){
                    return redirect()->back()->with(['success'=>'Data deleted successfully .']);

                }else{
                  return redirect()->back()->with(['error'=>'Sorry !something went wrong try agian later .']);
                }

            }else
            {
              return redirect()->back()->with(['error'=>'Sorry! unable to get required data .']);
            }
        }
        catch(\Exception $ex)
        {
            return redirect()->back()->with(['error'=>'Sorry ! Somthing went error ..'.$ex->getMessage()]);
        }
    }
    public function show($id)
    {
       $data=get_cols_where_row(new Inv_Itemcard(),array("*"),array('id'=>$id));
       $com_code=auth()->user()->com_code;
       $data['added_by_admin']=get_field_value(new Admin(),'name',array('id'=>$data['added_by']));
       $data['inv_itemcard_categories_name']=get_field_value(new Inv_Itemcard_Categories(),'name',array('id'=>$data['inv_itemcard_categories_id']));
       $data['parent_item_name']=get_field_value(new Inv_Itemcard(),'name',array('id'=>$data['parent_inv_itemcard_id']));
       $data['uom_name']=get_field_value(new Inv_Uom(),'name',array('id'=>$data['uom_id']));
       if($data['does_has_retailunit']==1){
        $data['retail_uom_name']=get_field_value(new Inv_Uom(),'name',array('id'=>$data['retail_uom_id']));
       }
       if($data['updated_by']>0 and $data['updated_by']!=null){
        $data['updated_by_admin']=get_field_value(new Admin(),'name',array('id'=>$data['updated_by']));
       }
       $inv_itemcard_movements_categories=Inv_Itemcard_Movement_Category::select()->orderby('id','ASC')->get();
       $inv_itemcard_movements_types=Inv_Itemcard_Movement_Type::select()->orderby('id','ASC')->get();
       $stores=get_cols_where(new Store(),array('id','name'),array('com_code'=>$com_code),'id','ASC');
       return view('admin.inv_itemcard.show',['data'=>$data,'stors'=>$stores,'inv_itemcard_movements_categories'=>$inv_itemcard_movements_categories,'inv_itemcard_movements_types'=>$inv_itemcard_movements_types]);
    }

    public function ajax_search(Request $request)
    {
      if($request->ajax())
      {
        $searchbyradio=$request->searchbyradio;

        $search_by_text=$request->search_by_text;
        $item_type_search=$request->item_type_search;
        $inv_itemcard_categories_id_search=$request->inv_itemcard_categories_id_search;


       
        if($item_type_search=='all')
        {
          $field1='id';
          $operator1='>';
          $value1=0;

        }else{
          $field1='item_type';
          $operator1='=';
          $value1=$item_type_search;

        }

        if($inv_itemcard_categories_id_search=='all')
        {
          $field2='id';
          $operator2='>';
          $value2=0;

        }else{
          $field2='inv_itemcard_categories_id';
          $operator2='=';
          $value2=$inv_itemcard_categories_id_search;

        }

        if($search_by_text!=''){
            if($searchbyradio=='barcode')
            {
                $field3='barcode';
                $operator3='=';
                $value3=$search_by_text;
            }elseif($searchbyradio=='item_code')
            {
                $field3='item_code';
                $operator3='=';
                $value3=$search_by_text;  
            }else
            {
                $field3='name';
                $operator3='LIKE';
                $value3="%{$search_by_text}%";
            }

        }else{
            $field3='id';
            $operator3='>';
            $value3=0;
        }

        //
        

        $data=Inv_Itemcard::where($field1,$operator1,$value1)->where($field2,$operator2,$value2)->where($field3,$operator3,$value3)->orderby('id','DESC')->paginate(PAGINATION_COUNT);
        if(!empty($data))
        {
            foreach ($data as $info) {
                $info->added_by_admin=get_field_value(new Admin(),'name',array('id'=>$info->added_by));
                $info->inv_itemcard_categories_name=get_field_value(new Inv_Itemcard_Categories(),'name',array('id'=>$info->inv_itemcard_categories_id));
                $info->parent_item_name=get_field_value(new Inv_Itemcard(),'name',array('id'=>$info->parent_inv_itemcard_id));
                $info->uom_name=get_field_value(new Inv_Uom(),'name',array('id'=>$info->uom_id));
                $info->retail_uom_name=get_field_value(new Inv_Uom(),'name',array('id'=>$info->retail_uom_id));
                if($info->updated_by>0 and $info->updated_by!=null)
                {
                    $info->updated_by_admin=get_field_value(new Admin(),'name',array('id'=>$info->updated_by));
                    
                }

            }
        }
        
        return view('admin.inv_itemcard.ajax_search',['data'=>$data]);
      }
    }


}
