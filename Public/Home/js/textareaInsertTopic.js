/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//点击广播区话题/关键字按钮，添加话题/关键字   
//获取话题按钮与关键字按钮对象

//var oButton = document.getElementById(a), kButton = document.getElementById(b);
////获取文本框对象                           
//var oTextarea = document.getElementById(c);
//设置要插入的提示字
var TOPIC = "输入话题";
var KEYWORD = "输入搜索关键字";
//设置功能函数
var funGetSelected = function(element) {
    if (!window.getSelection) { 
        //IE浏览器
        return document.selection.createRange().text;
    } else {
        return element.value.substr(element.selectionStart, element.selectionEnd - element.selectionStart);
    }
}, funInsertTopic = function(textObj,topic) {
    var value = textObj.value, index = value.indexOf(topic);
    if (index === -1) {
        //匹配
        funTextAsTopic(textObj, topic);
    } 
    value = textObj.value;
    index = value.indexOf(topic);
    if (textObj.createTextRange) {
        var range = textObj.createTextRange();
        //移动光标使之选中提示字
        range.moveEnd("character", -1 * value.length)           
        range.moveEnd("character", index + topic.length-1);
        range.moveStart("character", index + 1);
        range.select();    
    } else {
        textObj.setSelectionRange(index + 1, index + topic.length-1);
        textObj.focus();
    }
}, funTextAsTopic = function(textObj, textFeildValue) {
    textObj.focus();
    if (textObj.createTextRange) {
        var caretPos = document.selection.createRange().duplicate();
        document.selection.empty();
        caretPos.text = textFeildValue;
    } else if (textObj.setSelectionRange) {
        var rangeStart = textObj.selectionStart;
        var rangeEnd = textObj.selectionEnd;
        var tempStr1 = textObj.value.substring(0, rangeStart);
        var tempStr2 = textObj.value.substring(rangeEnd);
        textObj.value = tempStr1 + textFeildValue + tempStr2;
        textObj.blur();
    }
};
//oButton.onclick = function() {
//    var textSelection = funGetSelected(oTextarea);
//    var topic = "#" + TOPIC + "#";
//    if (!textSelection || textSelection === TOPIC) {
//        //没有文字选中，光标处插入
//        funInsertTopic(oTextarea,topic);    
//    } else {
//        funTextAsTopic(oTextarea, "#" + textSelection + "#");
//    }
//};
function setTopic(textareaName) {
var oTextarea = document.getElementById(textareaName);
    var textSelection = funGetSelected(oTextarea);
    var topic = "#" + TOPIC + "#";
    if (!textSelection || textSelection === TOPIC) {
        //没有文字选中，光标处插入
        funInsertTopic(oTextarea,topic);    
    } else {
        funTextAsTopic(oTextarea, "#" + textSelection + "#");
    }
}
//kButton.onclick = function() {
//    var textSelection = funGetSelected(oTextarea);
//    var topic = "{" + KEYWORD + "}";
//    if (!textSelection || textSelection === TOPIC) {
//        //没有文字选中，光标处插入
//        funInsertTopic(oTextarea,topic);    
//    } else {
//        funTextAsTopic(oTextarea, "{" + textSelection + "}");
//    }
//}; 
function setKeyWord(textareaName) {
var oTextarea = document.getElementById(textareaName);
    var textSelection = funGetSelected(oTextarea);
    var topic = "{" + KEYWORD + "}";
    if (!textSelection || textSelection === TOPIC) {
        //没有文字选中，光标处插入
        funInsertTopic(oTextarea,topic);    
    } else {
        funTextAsTopic(oTextarea, "{" + textSelection + "}");
    }
}



