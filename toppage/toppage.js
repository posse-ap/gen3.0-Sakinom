'use strict';

{
  const iconLine = document.getElementById('js-iconLine');
  
  let click = 0;
  
  const clicked = document.getElementById('menu-btn-check').onclick = function(){
    click += 1;
    
    const fixedLine = document.getElementById('js-fixedLine');
    fixedLine.classList.toggle('clicked');
    
    const lineLink = document.getElementById('js-lineLink');
    lineLink.classList.toggle('clicked-link');
    
    const content = 'POSSE公式LINE追加';
    const fixedIcon = document.getElementById('js-fixedIcon');

    if(click % 2 === 1){
      iconLine.innerHTML = content;
      fixedIcon.innerHTML = `<div class="p-footer_siteinfo">
      <a href="http://posse-ap.com/" target="_blank" rel="noopener noreferrer" class="p-footer_siteinfo_link">POSSE公式サイト</a>
      <i class="p-footer_icon_link"></i>
    </div>
    <div class="p-footer_sns">
      <ul class="p-sns_list p-footer_sns_list">
        <li class="p-sns_item js-snsItem">
          <a href="http://twitter.com/posse_program" target="_blank" rel="noopener noreferrer" class="p-sns_item_link" aria-label="Twitter">
            <i class="u-icon_twitter"></i>
          </a>
        </li>
        <li class="p-sns_item js-snsItem">
          <a href="http://www.instagram.com/posse_programming/" target="_blank" rel="noopener noreferrer" class="p-sns_item_link" aria-label="instagram">
            <i class="u-icon_instagram"></i>
          </a>
        </li>
      </ul>
    </div>`
    }else{
      iconLine.innerHTML = 'POSSE公式LINEで<br>最新情報をGET！';
      fixedIcon.innerHTML = '';
    }
  }
    
    // const snsItem = document.querySelectorAll('js-snsItem');
    // snsItem.forEach(element => {
    //   element.classList.toggle('p-sns_item_checked');
    // });
  }
  
  // <div class="p-fixedLine" id="js-fixedLine">
  //   <a href="http://line.me/R/ti/p/@651htnqp?from=page" target="_blank" rel="noopener noreferrer" class="p-fixedLine_link" id="js-lineLink">
  //   <i class="u-icon_line"></i>
  //   <p class="p-fixedLine_line_text" id="js-iconLine">POSSE公式LINEで<br>最新情報をGET！</p>
  //   <i class="u-icon_link"></i>
  // </a>
  // </div>