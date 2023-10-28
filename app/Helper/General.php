<?php

use App\Models\Inv_Itemcard;
use App\Models\Inv_Itemcard_Batch;
use App\Models\Sale_Invoice;
use App\Models\Treasury_Transaction;
use Illuminate\Support\Facades\Config;

function uploadImage($folder,$image)
{
 $extension=strtolower($image->extension());
 $filename=time().rand(100,999).'.'.$extension;
 $image->getClientOriginalName=$filename;
 $image->move($folder,$filename);
 return $filename;

}
/*-----------get some cols where with pagination--------------------*/
 function get_cols_where_p($model,$columns_names=array(),$where=array(),$order_field,$order_type,$pagination_count)
{
 $data=$model::select($columns_names)->where($where)->orderby($order_field,$order_type)->paginate($pagination_count);
 return $data;
}
/*-----------get some cols where--------------------*/
function get_cols_where($model,$columns_names=array(),$where=array(),$order_field,$order_type)
{
 $data=$model::select($columns_names)->where($where)->orderby($order_field,$order_type)->get();
 return $data;
}
/*-----------get some cols--------------------*/
function get_cols($model,$columns_names=array(),$order_field,$order_type)
{
 $data=$model::select($columns_names)->orderby($order_field,$order_type)->get();
 return $data;
}
/*-----------get cols where row-------------*/
function get_cols_where_row($model,$columns_names=array(),$where=array())
{
 $data=$model::select($columns_names)->where($where)->first();
 return $data;
}
/*----------- item code -------------*/
function get_cols_where_row_orderby($model,$columns_names=array(),$where=array(),$order_field='id',$order_type='DESC')
{
 $data=$model::select($columns_names)->where($where)->orderby($order_field,$order_type)->first();
 return $data;
}
/*------------insert_into_table------------ */
function insert($model,$arrayToInsert=array(),$returnData=false)
{
    $flag=$model::create($arrayToInsert);
    if($returnData==true){
        $data=get_cols_where_row($model,array('*'),$arrayToInsert);
        return $data;
    }
    return $flag;
}
/*------------get_field_value--------------*/
function get_field_value($model,$field_name,$where)
{
    $data=$model::where($where)->value($field_name);
    return $data;
}
/*------------update------------------------*/
function update($model=null,$data_to_update=array(),$where=array())
{
    $flag=$model::where($where)->update($data_to_update);
    return $flag;
}
/*---------------get_cols_where2_row---------------------*/
function get_cols_where2_row($model,$columns_names=array(),$where=array(),$where2=array())
{
    $data=$model::select($columns_names)->where($where)->where($where2)->first();
    return $data;
}
/*---------------delete row wher ---------------------*/
function delete($model=null,$where=array())
{
    $flag=$model::where($where)->delete();
    return $flag;
}
/*--------------- get sum where ---------------------*/
function get_sum_where($model,$field_name,$where)
{
    $flag=$model::where($where)->sum($field_name);
    return $flag;
}
/*--------------- refresh account balance ---------------------*/
function refresh_account_balance_supplier($account_number=null,$AccountModel=null,$SupplierModel=null,$Treasury_TransactionModel=null,$suppliers_with_ordersModel=null,$returnFlag=false){
    $com_code=auth()->user()->com_code;
    //الرصيد الافتتاحي للرصيد لحظة تكويده
   $AccountData=$AccountModel::select('start_balance','account_type')->where(['com_code'=>$com_code,'account_number'=>$account_number])->first();
   if($AccountData['account_type']==2){
    //صافي مجموع المشتريات والمرتجعات للمورد
  $the_net_in_suppliers_with_orders=$suppliers_with_ordersModel::where(['com_code'=>$com_code,'account_number'=>$account_number])->sum('money_for_account');
  //صافي حركة النفدية باالخزن على حساب المورد 
  $the_net_in_treasuries_transactions=$Treasury_TransactionModel::where(['com_code'=>$com_code,'account_number'=>$account_number])->sum('money_for_account');
  //الرصيد النهاىي للمورد
  $the_final_balance=$AccountData['start_balance']+$the_net_in_suppliers_with_orders+$the_net_in_treasuries_transactions;
  $dataToUpdateAccount['current_balance']=$the_final_balance;
  //update in accounts
  $AccountModel::where(['com_code'=>$com_code,'account_number'=>$account_number])->update($dataToUpdateAccount);
  $datatoUpdateSupplier['current_balance']=$the_final_balance;
  $SupplierModel::where(['com_code'=>$com_code,'account_number'=>$account_number])->update($datatoUpdateSupplier);
  if($returnFlag){
    return $the_final_balance;
  }
}

}
/*-----------------refresh_account_balance_customer-------------*/
function refresh_account_balance_customer($account_number=null,$AccountModel=null,$Sale_InvoiceModel=null,$Treasury_TransactionModel=null,$customerModel=null,$returnFlag=false)
{
 $com_code=auth()->user()->com_code;
 //first balance for the account
 $AccountData=$AccountModel::select('start_balance','account_type')->where(['com_code'=>$com_code,'account_number'=>$account_number])->first();
 if($AccountData['account_type']==3){
    $the_net_sales_invoicesForCustomer=$Sale_InvoiceModel::where(['com_code'=>$com_code,'account_number'=>$account_number])->sum('money_for_account');
    $the_net_sales_invoicesReturnForCustomer=0;
    $the_net_in_treasuries_transactions=$Treasury_TransactionModel::where(['com_code'=>$com_code,'account_number'=>$account_number])->sum('money_for_account');
    $the_final_balance=$AccountData['start_balance']+$the_net_sales_invoicesForCustomer+$the_net_sales_invoicesReturnForCustomer+$the_net_in_treasuries_transactions;
    $dataToUpdateAccount['current_balance']=$the_final_balance;
    $AccountModel::where(['com_code'=>$com_code,'account_number'=>$account_number])->update($dataToUpdateAccount);
    $datatoUpdateSupplier['current_balance']=$the_final_balance;
    $customerModel::where(['com_code'=>$com_code,'account_number'=>$account_number])->update($datatoUpdateSupplier);
    if($returnFlag){
        return $the_final_balance;
    }

 }
}
/*-----------------refresh_account_balance_general-------------*/
function refresh_account_balance_general($account_number=null,$AccountModel=null,$Treasury_TransactionModel=null,$returnFlag=null)
{
    $com_code=auth()->user()->com_code;
    $AccountData=$AccountModel::select('start_balance','account_type')->where(['com_code'=>$com_code,'account_number'=>$account_number])->first();
    if($AccountData['account_type']!=2 and $AccountData['account_type']!=3 and $AccountData['account_type']!=4 and $AccountData['account_type']!=5 and $AccountData['account_type']!=8)
    {
        $the_net_in_treasuries_transactions=$Treasury_TransactionModel::where(['com_code'=>$com_code,'account_number'=>$account_number])->sum('money_for_account');
        $the_final_balance=$AccountData['start_balance']+$the_net_in_treasuries_transactions;
        $dataToUpdateAccount['current_balance']=$the_final_balance;
        $AccountModel::where(['com_code'=>$com_code,'account_number'=>$account_number])->update($dataToUpdateAccount);
        if($returnFlag){
            return $the_final_balance;
        }
    }
}

/*-----------------get_user_shift------------------------------ */
function get_user_shift($Admin_shift,$Treasury=null,$Treasury_Transaction=null){
    $com_code=auth()->user()->com_code;
    $data=$Admin_shift::select('treasuries_id','shift_code')->where(['com_code'=>$com_code,'admin_id'=>auth()->user()->id,'is_finished'=>0])->first();
    if(!empty($data))
    {
        $data['name']=$Treasury::where(['id'=>$data['treasuries_id'],'com_code'=>$com_code])->value('name');
        $data['balance']=$Treasury_Transaction::where(['shift_code'=>$data['shift_code'],'com_code'=>$com_code])->sum('money');
    }

    return $data;
}

/*-----------------update item card------------------------------ */
function do_update_itemCardQuantity($Inv_Itemcard=null,$item_code=null,$Inv_Itemcard_Batch=null,$does_has_retailunit=null,$retail_uom_quntToParent=null,)
{
    $com_code=auth()->user()->com_code;
    //update itemcard Quantity mirror
    $allQuantityINBatches=$Inv_Itemcard_Batch::where(['com_code'=>$com_code,'item_code'=>$item_code])->sum('quantity');
    //all item amount in parent unit
    $DataToUpdateItemCardQuantity['ALL_QUANTITY']=$allQuantityINBatches;
    if($does_has_retailunit==1){
      //all quantity in retail
      $quantity_all_retails=$allQuantityINBatches*$retail_uom_quntToParent;
      $parentQuantityUom=intdiv($quantity_all_retails,$retail_uom_quntToParent);
      $DataToUpdateItemCardQuantity['quentity']=$parentQuantityUom;
      $DataToUpdateItemCardQuantity['quentity_retail']=fmod($quantity_all_retails,$retail_uom_quntToParent);
      $DataToUpdateItemCardQuantity['quentity_all_retails']=$quantity_all_retails;
    }else{
        $DataToUpdateItemCardQuantity['quentity']=$allQuantityINBatches;
    }
    update($Inv_Itemcard,$DataToUpdateItemCardQuantity,array('com_code'=>$com_code,'item_code'=>$item_code));
}
