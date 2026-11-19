document.querySelectorAll('form .checkbox').forEach(function(c){
  var f=c.closest('form'); var b=f.querySelector('button.btn-main'); if(!b||(b.compareDocumentPosition(c)&Node.DOCUMENT_POSITION_PRECEDING))return;
  var col=b.parentNode.classList.contains('col-btn')?b.parentNode:null; c.after(b); if(col)col.remove();
});
