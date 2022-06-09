 class dialogBox {
     constructor(messageType, title, content) {
         this.messageType = messageType;
         this.title = title;
         this.content = content;
     }

     createDialog() {
         //  $('body').append(`
         //     <div id="hbg"></div>				
         //     <div id="alertM">
         //         <div class="dialogHead">
         //             <h5 id="alertT">${this.title ? this.title : 'Title'}</h5>
         //             <!-- 关闭按钮 -->	
         //             <a id="alertR" title="Close" href="javascript:void(0)">×</a>
         //         </div>				
         //         <!-- 内容 -->
         //         <div class="dialogBody">   
         //             <p id="alertP">${this.content ? this.content : 'Contents'}</p>
         //         </div>
         //         <div id="alertBtns">
         //             <button id="alertY" title="OK" href="javascript:void(0)">Ok</button>
         //             ${this.messageType==2?'<button id="alertN" title="Close" href="javascript:void(0)">Cancel</button>' : ''}

         //         </div>
         //     </div>
         // `);
         $('body').append("\n            <div id=\"hbg\"></div>\t\t\t\t\n            <div id=\"alertM\">\n                <div class=\"dialogHead\">\n                    <h5 id=\"alertT\">" + (this.title ? this.title : 'Title') + "</h5>\n                    <!-- \u5173\u95ED\u6309\u94AE -->\t\n                    <a id=\"alertR\" title=\"Close\" href=\"javascript:void(0)\">\xD7</a>\n                </div>\t\t\t\t\n                <!-- \u5185\u5BB9 -->\n                <div class=\"dialogBody\">   \n                    <p id=\"alertP\">" + (this.content ? this.content : 'Contents') + "</p>\n                </div>\n                <div id=\"alertBtns\">\n                    <button id=\"alertY\" title=\"OK\" href=\"javascript:void(0)\">Ok</button>\n                    " + (this.messageType == 2 ? '<button id="alertN" title="Close" href="javascript:void(0)">Cancel</button>' : '') + "\n                    \n                </div>\n            </div>\n        ");
         //禁用body滚动条
         //  $('body').css({ 'overflow-y': 'hidden', 'overflow-x': 'hidden' });

         this.closeCommand();

         //定义页面宽度，页面高度，弹窗位置left，弹窗位置top，滚动条高度
         var screenWidth, screenHeight, tcleft, tctop, scrollTop;
         //计算弹窗位置的函数
         tanLocation();
         //窗口对象添加resize() 当浏览器窗口大小改变时执行。
         $(window).resize(function() {
                 console.log('当浏览器窗口大小改变时执行resize()')
                 tanLocation();
             })
             //文档对象添加scroll() 当滚轮高度变化时执行
         $(document).scroll(function() {
             console.log('当滚轮高度变化时执行scroll()')

             tanLocation();
         })

         //计算弹窗位置的函数
         function tanLocation() {
             //获取页面宽度
             screenWidth = $(window).width();
             //获取页面高度
             screenHeight = window.screen.height;
             //计算left值
             tcleft = (screenWidth - 100) / 2;
             //计算top值
             tctop = (screenHeight - 100) / 2;
             //获取滚轮高度
             scrollTop = $(document).scrollTop();
             //弹窗的位置样式改变
             //  $('#alertM').css({ 'top': tctop + scrollTop, 'left': tcleft });
             $('#alertM').css({ 'top': tctop + scrollTop });
             $('#hbg').css({ 'top': scrollTop });
         }
     }

     closeCommand() {
         $('#alertR').click(function() {
             $(this).parent().parent().remove();
             $('#hbg').remove();
             $('body').removeAttr('style');

         })
         $('#alertY').click(function() {
             $(this).parent().parent().remove();
             $('#hbg').remove();
             $('body').removeAttr('style');
         });

         $('#alertN').click(function() {
             $(this).parent().parent().remove();
             $('#hbg').remove();
             $('body').removeAttr('style');
         });
         return
     }
 }