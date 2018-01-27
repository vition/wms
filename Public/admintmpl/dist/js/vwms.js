/** 
 * @Author: vition 
 * @Date: 2018-01-23 00:41:37 
 * @Desc: get方式获取数据 
 */    
function get(url,datas,callBack){
    $.ajax({
        url:url,
        type:'get',
        dataType:'json',
        data:datas,
        success:callBack,
    })
/** 
 * javascript comment 
 * @Author: vition 
 * @Date: 2018-01-27 22:43:43 
 * @Desc: post发送 
 */}
function post(url,datas,callBack){
    $.ajax({
        url:url,
        type:'post',
        dataType:'json',
        data:datas,
        success:callBack,
    })
}

var datas={}
/** 
 * javascript comment 
 * @Author: vition 
 * @Date: 2018-01-27 18:19:11 
 * @Desc: 所有搜索按钮触发事件 
 */
$(document).on("click",".search-list,.vpage",function(){
    datas={}
    var page=$(this).data('page')
    if(page>0){
        datas['p']=page;
        var url=$(this).parents('.page-div').data("url");
        var con=$(this).parents('.page-div').data("con");
        var reqtype=$(this).parents('.page-div').data("reqtype");
    }else{
        var url=$(this).data("url");
        var con=$(this).data("con");
        var reqtype=$(this).data("reqtype");
    }
    var table=con+"Table";
    var page=con+"Page";
    datas.reqType=reqtype;
    eval(con+"SearchFuns()");//对不同的id设置不同的发送数据
    
    get(url,datas,function(result){
        if(result.errCode==0){
            $("#"+table).html(result.table);
            $("#"+page).html(result.page);
        }else{
            alert(result.error);
        }
    })
})
/** 
 * javascript comment 
 * @Author: vition 
 * @Date: 2018-01-27 18:19:36 
 * @Desc: 所有编辑，添加，触发弹出modal事件 
 */
$(document).on("click",".info-edit",function(){

    var target=$(this).data("target");
    var title=$(this).data("title");
    var reqtype=$(this).data("reqtype");
    var show=$(this).data("show");
    var con=$(this).data("con");
    $(target).find('.modal-title').text(title)
    $(target).find('.save-info').text(title)
    $(target).find('.save-info').data("reqtype",reqtype)
    if(show=='One'){//编辑要获取数据
        datas={}
        var id=$(this).data("id");
        var url=$(this).data("url");
        
        datas.reqType=con+show;
        datas.id=id
        get(url,datas,function(result){
            if(result.errCode==0){
                eval(con+"ShowFuns(result.info)");//对不同的模块设置不同的响应数据

            }else{
                alert(result.error);
            }
        })
    }else{//新建要重置数据
        $(target).find(".modal-info").val("");
        eval(con+"ReFuns()");//对不同的模块的modal数据重置
    }
})
/** 
 * javascript comment 
 * @Author: vition 
 * @Date: 2018-01-27 18:19:54 
 * @Desc: 所有状态选择按钮事件 
 */
$(document).on("click",'.status-btn',function(){
    $(this).parents(".status-group").children(".status-btn").removeClass("active");
    $(this).addClass("active");
    var status=$(this).attr("name");
    $(this).parents(".status-group").children("input[name='status']").val(status);
})
/** 
 * javascript comment 
 * @Author: vition 
 * @Date: 2018-01-27 22:09:37 
 * @Desc: 所有重置按钮 
 */
$(document).on("click",'.search-refresh',function(){
    $(this).parents(".search-body").find(".search-info").val("");
    var con=$(this).data("con")
    eval(con+"ResetFuns()");//弥补不足
})
/** 
 * javascript comment 
 * @Author: vition 
 * @Date: 2018-01-27 22:38:31 
 * @Desc: 保存数据、新增或修改 
 */
$(document).on("click",'.save-info',function(){
    datas={}
    var url=$(this).data("url");
    var reqtype=$(this).data("reqtype");
    var con=$(this).data("con");
    datas.reqType=reqtype;
    eval(con+"InfoFuns()");//对不同的id设置不同的发送数据
    console.log(datas)
    // post(url,datas,callBack);
})
