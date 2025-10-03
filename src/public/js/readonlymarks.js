
var clog = function(){
    //
};

var cdir = function(){
    //
};


function jumpDown(node)
{
    let cnode = node.firstElementChild;
    if (cnode === null || node === undefined) return node;
    cdir(cnode);
    try {
            if (cnode.tagName == "EM") node = cnode;
            if (cnode.tagName == "CODE") node = cnode;
            if (cnode.tagName == "SUP") node = cnode;
            if (cnode.tagName == "SUB") node = cnode;
    } catch (err) {
        //
    }
    return node;
}



let blinkMark = function (node)
{
    if (node === null || node === undefined) return;

    node = jumpDown(node);

    let _node = node;
    let blinkTimer = undefined;

    return (function(){
        $(_node).addClass("flash");
        //.delay(1200).removeClass("flash");
        clearTimeout(blinkTimer);
        blinkTimer = setTimeout(function(){
            $(_node).removeClass("flash");
            clog("flash free");
        }, 550);
    }());
};




let doNotRemove = function (event) {
    
    cdir(event);


    const keyCodeEnter = 13;
    const keyCodeCommand = 91;
    const keyCodeC = 67;

    if (event.which == 13) { // enter
        event.preventDefault();
        return;
    }


    // разрешаем копировать
    if (event.keyCode == 91) { // command
        return;
    }
    if (event.keyCode == 67 && event.metaKey == true) { // comand + key C
        return;
    }

    // фильтруем вставку
    if (event.type === "paste")
    {
        event.preventDefault();
        let cb = (event.originalEvent || event).clipboardData;
        let html = cb.getData('text/html');
        let text = cb.getData('text/plain');

        // Do something with paste like remove non-UTF-8 characters
        //text = text.replace(/[^\x20-\xFF]/gi, '');

        text = text.replace(/\s+/g, " "); // запрещаем вставлять текст с переносами строк
        document.execCommand('insertText', false, text);
        clog("insert", text);
        return;
    }





    let selection = window.getSelection();
    let range = selection.getRangeAt(0);
    clog(range);

    //if (window.flag) event.preventDefault();


    if (range.collapsed === false)
    {
        let node = range.startContainer;
        let stop = false;
        for (var i=0; i < 100; i++)
        {
            if (node === range.endContainer || node === null) break;

            let node2 = jumpDown(node);
            cdir(node2);

            if (node2.isContentEditable === false) {

                blinkMark(node2);

                clog("has tag", node2);
                event.preventDefault();
                stop = true;
            }

            node = node.nextSibling;
        }
        if (stop) return;


    } else {

        if (range.startContainer === window.editorActiveElement) {
            event.preventDefault();
            let fce = range.startContainer.firstElementChild;
            if (fce !== null && $(fce).hasClass("fastEdit_mark")) {
                blinkMark(fce);
            }

            clog("qqqqq");
        }


        let prev = range.startContainer.previousSibling;
        let next = range.startContainer.nextSibling;
        let rnlen = range.startContainer.textContent.length;

        //clog(prev); OPTIMIZE THIS CODE!!!!!!!!!!!!!!!
        var test = document.createElement ("span");
        test.innerHTML = "&nbsp;";
        var mytext = test.textContent;
        // --- OPTIMIZE THIS CODE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!



        if (event.which == 8) { // backspace
            if (rnlen < 2 || range.startOffset == 0) {
                if (rnlen < 2) range.startContainer.textContent = mytext;
                event.preventDefault();
                blinkMark(prev);
            }
        }

        if (event.which == 46) { // del
            if (rnlen < 2 || range.endOffset == rnlen) {
                if (rnlen < 2) range.startContainer.textContent = mytext;
                event.preventDefault();
                blinkMark(next);
            }
        }
    }
    // =======

};

let wrapForMarks = function(elem) {

    window.editorActiveElement = elem;

    $(elem).children("*").each(function(idx, elem){
    
        clog(idx, elem);
        let el = $(elem);
        
        if (el.is(window.matlab_corrections_selectors)) return;
        
        el.addClass("fastEdit_mark");
        el.attr('contenteditable', 'false');
    });

    $(elem).on('keydown', doNotRemove);

    //$(elem).bind('copy', doNotRemove); 
    $(elem).bind('paste', doNotRemove); 
    $(elem).bind('cut', doNotRemove);
};

let unwrapForMarks = function(elem) {
    $(elem).find(".fastEdit_mark").each(function(a, b){
        clog(a, b);
        let el = $(b);
        el.removeClass("fastEdit_mark");
        el.removeAttr('contenteditable');
        console.log('--');
    });
};


window.editorActiveElement = undefined; // used in doNotRemove()
window.wrapForMarks = wrapForMarks;
window.unwrapForMarks = unwrapForMarks;







