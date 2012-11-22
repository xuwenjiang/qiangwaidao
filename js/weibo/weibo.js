/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

//http://open.weibo.com/wiki/API%E6%96%87%E6%A1%A3_V2#OAuth2  weibo API
//http://open.weibo.com/wiki/index.php/Weibo-JS_V2#WB2.anyWhere.28callback.29 call method
//$(document).ready(function()
//{
    WB2.anyWhere(function(W){
        W.widget.connectButton({
            id: "wb_connect_btn",
            type: '3,2',
            callback : {
                login:function(o){
                    weiboLoginCallback(o);
                },
                logout:function(){
                    weiboLogoutCallback();
                }
            }
        });
    })
//});