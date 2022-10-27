'use strict';

console.clear();

{  
  const today = new Date();
  let year = today.getFullYear();
  let month = today.getMonth(); //5月


  function getCalendarHead() {
    const dates = [];
    const d = new Date(year, month, 0).getDate();
    const n = new Date(year, month, 1).getDay();

    for (let i = 0; i < n; i++) {
      // 30
      // 29, 30
      // 28, 29, 30
      dates.unshift({
        date: d - i,
        isToday: false,
        isDisabled: true,
      });
    }

    return dates;
    // console.log(dates);
  }


  function getCalendarBody() {
    const dates = []; //date: 日付, day:曜日
    const lastDate = new Date(year, month + 1, 0).getDate();

    for (let i = 1; i <= lastDate; i++) {
      dates.push({
        date: i,
        isToday: false,
        isDisabled: false,
      });
    }

    if (year === today.getFullYear() && month === today.getMonth()) {
      dates[today.getDate() - 1].isToday = true;
    }

    return dates;
    // console.log(dates);
  }


  function getCalendarTail() {
    const dates = [];
    const lastDay = new Date(year, month + 1, 0).getDay();

    for (let i = 1; i < 7 - lastDay; i++) {
      dates.push({
        date: i,
        isToday: false,
        isDisabled: true,
      });
    }

    return dates;
    // console.log(dates);
  }


  function clearCalendar() {
    const tbody = document.querySelector('tbody');

    while (tbody.firstChild) {
      tbody.removeChild(tbody.firstChild);
    }
  }


  function renderTitle() {
    const title = `${year}年${String(month + 1).padStart(2, '0')}月`; //padStartは文字列についてしか使えないからString使う
    document.getElementById('title').textContent = title;
  }


  function renderWeeks() {
    const dates = [
      ...getCalendarHead(),
      ...getCalendarBody(),
      ...getCalendarTail(),
    ];
    const weeks = [];
    const weeksCount = dates.length / 7;

    for (let i = 0; i < weeksCount; i++) {
      weeks.push(dates.splice(0, 7));
    }

    weeks.forEach(week => {
      const tr = document.createElement('tr');
      week.forEach(date => {
        const td = document.createElement('td');

        td.textContent = date.date;
        if (date.isToday) {
          td.classList.add('today');
        }
        if (date.isDisabled) {
          td.classList.add('disabled')
        }
        tr.appendChild(td);

          td.addEventListener('click', () => {
            
            const yearMonth = document.getElementById('title');
            const selectedDate = yearMonth.innerHTML+td.innerHTML+'日';
            console.log(selectedDate)
            const inputDate = document.getElementById('date');
            inputDate.value = selectedDate;
            const modalCalendar = document.querySelector('.modal_calendar');
            const modalMain = document.querySelector('.modal_main');
            modalCalendar.style.display = 'none';
            modalMain.style.display = 'block';

        })
      });
      document.querySelector('tbody').appendChild(tr);
    })
  }


  function createCalendar() {
    clearCalendar();
    renderTitle();
    renderWeeks();
  }


  document.getElementById('prev').addEventListener('click', () => {
    month--;
    if (month < 0) {
      year--;
      month = 11;
    }

    createCalendar();
  })


  document.getElementById('next').addEventListener('click', () => {
    month++;
    if (month > 11) {
      year++;
      month = 0;
    }

    createCalendar();
  });


  // document.getElementById('today').addEventListener('click', () => {
  //   year = today.getFullYear();
  //   month = today.getMonth();

  //   createCalendar();
  // });


  createCalendar();

  // dates.forEach(date => {
  //   date.addEventListener('click', () => {
  //     console.log(date)
  //   })
  // })

  /***********************************************
      日付を取得
 ***********************************************/
{
  // const mytable = document.getElementById("tbl");
  // for (var i=0; i < mytable.rows.length; i++) {
  //   for (var j=0; j < mytable.rows[i].cells.length; j++) {
  //     console.log(  "(" + i + "," + j + ") : " + mytable.rows[i].cells[j].innerHTML  );
  //   }}

  //   for (var i=0; i < mytable.rows.length; i++) {
  //     for (var j=0; j < mytable.rows[i].cells.length; j++) {
  //       mytable.rows[i].cells[j].id = i + "-" + j;
  //       mytable.rows[i].cells[j].onclick = clicked;
  //     }
  //   }
     
  //   function clicked(e) {
  //     var txt = document.getElementById("result");
  //     txt.textContent = e.target.id + "がクリックされました。値は：" + e.target.innerHTML;
  //   }
    
  // const dates = document.getElementsByTagName("td").innerHTML;

  // console.log(dates)

  // dates.forEach(date => {
  //   date.addEventListener('click', () => {
  //     console.log(date);
  //   })
  // })
}
}