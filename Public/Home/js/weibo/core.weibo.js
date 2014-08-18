/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

core.weibo = {
    init : function(){
	
    },
    LoadMoreWeibo : function(){

        $.get("{:U('Home/moreBlogs')}?unit={$unit}&timestamp=" + timestamp + "&memberId={$nowmember}" + "&t=" + new Date().getTime(), function(json) {
         if(json == "error"){
		    showTipMsg(json);
	    }else{	    
    		var r = eval("(" + json + ")");
		if (r['msg'] == 'have no tweet') {
		    $('#moreWords').html('没有更多微博');
		}else{
    		    var l = r.length;
    		    timestamp = r[l - 1]['timestamp'];
    		    $("#talkList").append(structBlogs(r));
		}
	    }
        });
	
	
	$.ajax({
	    type:'get',
	    url : '{:W("FreePrecious")}',
	    data : {unit:unit,timestamp:timestamp,memberId:nowmember,t: new Date().getTime()},
	    dataType:'text',
	    
	    success : function(msg){
		// 加载失败
		if(msg.status == "0" || msg.status == "-1") {
			$('#loadMore').remove();
			if(msg.status == 0 && ("undefined" != typeof(msg.msg)) && _this.loadmore > 0) {
				$('#feed-lists').append('<div class="loading" id="loadMore">' + msg.msg + '</div>');
			}
		}
		// 加载成功
		if(msg.status == "1") {    	    
		    var r = eval("(" + json + ")");
		    if (r['msg'] == 'have no tweet') {
			$('#moreWords').html('没有更多微博');
		    }else{
			var l = r.length;
			timestamp = r[l - 1]['timestamp'];
			$("#talkList").append(structBlogs(r));
		    }
		}
	    }
	});
    }
};
