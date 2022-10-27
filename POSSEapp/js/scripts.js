'use strict';

{
  const button = document.querySelector('.header_btn');
  // console.log(button);
  const modal = document.querySelector('.modal');
  const modalMain = document.querySelector('.modal_main');
  const modalLoading = document.querySelector('.modal_loading');

  button.addEventListener('click', () => {
    modal.style.display = 'block';
    modalLoading.style.display = 'none';
    complete.style.display = 'none';
    modalMain.style.display = 'block';
  });

  const close = document.querySelectorAll('.modal_close');
  close.forEach(btn => {
    btn.addEventListener('click', () => {
      console.log('clicked');
      modal.style.display = 'none';
    })
  })

  const complete = document.querySelector('.modal_complete');
  const changeModal = () => {
    complete.style.display = 'block';
    modalLoading.style.display = 'none'
  }

  const modalBtn = document.querySelector('.modal_btn');

  modalBtn.addEventListener('click', () => {
    // console.log('hello');
    modalMain.style.display = 'none';
    modalLoading.style.display = 'block';
    if(document.getElementById('share').checked) {
      openTwitter();
    }
    setTimeout(changeModal, 5000);
  })

  const dateInput = document.getElementById('date');
  const modalCalendar = document.querySelector('.modal_calendar');
  const modalReturn = document.querySelector('.modal_return');

  dateInput.addEventListener('click', () => {
    modalMain.style.display = 'none';
    modalCalendar.style.display = 'flex';
  })

  modalReturn.addEventListener('click', () => {
    modalCalendar.style.display = 'none';
    modalMain.style.display = 'block';
  })

    //openTwitter(投稿文、シェアするURL、ハッシュタグ、提供元アカウント)
    function openTwitter() {
      const comment = document.getElementById('comment').value;
      var turl = "https://twitter.com/intent/tweet?text=" + comment;
      window.open(turl, '_blank');
    }

}

/*******************************************************
    グラフの作成
*******************************************************/

(function () {
  'use strict';

  var type = 'bar';
  var ctx = document.getElementById('datasets_bar').getContext('2d');
  var gradient = ctx.createLinearGradient(0, 10, 0, 400);
  gradient.addColorStop(0, 'rgb(63,206,254)');
  gradient.addColorStop(0.5, 'rgb(17,116,190)');

  var data = {

    labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31],
    datasets: [{
      label: 'study hours',
      data: [2.3, 4.5, 1.2, 3.7, 3.7, 4, 6, 7.5, 1.5, 4, 2, 5.5, 7, 8, 5.5, 3.7, 4, 0.6, 0.6, 1, 4, 2.3, 5.5, 1.5, 6, 8, 8, 2, 0.6, 3.7, 1.2],
      backgroundColor: gradient,
      borderWidth: 0
    }]
  };

  var options = {
    scales: {
      yAxes: [{
        ticks: {
          min: 0,
          max: 8,
          stepSize: 2,
          callback: function (value, index, values) {
            return value + 'h';
          }
        },
        gridLines: {
          display: false,
          drawBorder: false
        }
      }],
      xAxes: [{
        ticks: {
          // maxRotation: 0,
          // minRotation: 0,
          callback: function (value, index, values) {
            if (index % 2 === 0) {
              return "";
            }
            return value;
          }
        },
        barThickness: 5,
        gridLines: {
          display: false,
          drawBorder: false
        },
      }]
    },
    legend: {
      display: false
    }
  };

  var myChart = new Chart(ctx, {
    type: type,
    data: data,
    options: options
  });
})();

{
  var dataLabelPlugin = {
    afterDatasetsDraw: function (chart) {
      var ctx = chart.ctx;
      chart.data.datasets.forEach(function (dataset, 系列) {
        var meta = chart.getDatasetMeta(系列);
        if (!meta.hidden) {
          meta.data.forEach(function (element, 要素) {
            // ステップ１　数値を文字列に変換
            // dataSum += dataset.data[要素];
            // var ratio = dataset.data[要素]/dataSum*100;
            // var dataString = ratio.toString();
            var dataString = dataset.data[要素].toString();
            // ステップ２　文字列の書体
            ctx.fillStyle = "#fff";            // 色　'rgb(0, 0, 0)', 'rgba(192, 80, 77, 0.7)'
            var fontSize = 14;                  // サイズ
            var fontStyle = "normal";           // 書体 "bold", "italic"
            // var fontFamily = "serif";           // フォントの種類 "sans-serif", "ＭＳ 明朝"
            ctx.font = Chart.helpers.fontString(fontSize, fontStyle);
            // ステップ３　文字列の位置の基準点
            ctx.textAlign = 'center';           // 文字列　start, end, left, right, center
            ctx.textBaseline = 'middle';        // 文字高　middle, top, bottom
            // ステップ４　文字列のグラフでの位置
            var padding = 5;                   // 点と文字列の距離
            var position = element.tooltipPosition(); //文字列の表示　 fillText(文字列, Ｘ位置, Ｙ位置)
            ctx.fillText(dataString + '%', position.x, position.y - (fontSize / 2) - padding);
          });
        }
      });
    }
  };

  (function () {
    'use strict';

    var type = 'doughnut';

    var data = {
      labels: ['HTML', 'CSS', 'JavaScript', 'PHP', 'Laravel', 'SQL', 'SHELL', '情報システム基礎知識（その他）'],
      datasets: [{
        data: [30, 20, 10, 5, 5, 20, 20, 10],
        backgroundColor: ['	#0042E5', '	#0070BA', '	#02BDDB', '	#04CDFA', '	#B39DED', '	#6C44E6', '	#4609E8', '	#2B01BA'],
        pointStyle: 'circle',
      }]
    };

    var options = {
      cutoutPercentage: 40,
      ticks: [{
        callback: function (value, index, values) {
          return value + '%';
        }
      }],
      legend: {
        position: 'bottom',
        // align: 'start',
        labels: {
          usePointStyle: true,
        }
      },
      responsive: true,
      maintainAspectRatio: false,
      // plugins: {
      //   tooltip: {
      //     enabled: 'false'
      //   }
      // }
    };

    var ctx = document.getElementById('pie-charts_lang').getContext('2d');
    var myChart = new Chart(ctx, {
      type: type,
      data: data,
      options: options,
      plugins: [dataLabelPlugin]
    });
  })();


  (function () {
    'use strict';

    var type = 'doughnut';

    var data = {
      labels: ['ドットインストール', ' N予備校', ' POSSE課題'],
      datasets: [{
        data: [40, 20, 40],
        backgroundColor: ['#0042E5', '#0070BA', '#02BDDB'],
        pointStyle: 'circle',
        // textAlign: 'left',
      }]
    };

    var options = {
      cutoutPercentage: 40,
      ticks: [{
        display: true,
        callback: function (value, index, values) {
          return value + '%';
        }
      }],
      legend: {
        position: 'bottom',
        // align: 'end',
        labels: {
          usePointStyle: true,
        }
      },
      responsive: true,
      maintainAspectRatio: false,
      // plugins: {
      //   datalabels: {
      //     color: 'white',
      //     fontSize: 50,
      //     formatter: function (value, context) {
      //       return value + '%'; // データラベルに文字などを付け足す
      //   },
      //   }
      // }
    };

    var ctx = document.getElementById('pie-charts_content').getContext('2d');
    var myChart = new Chart(ctx, {
      type: type,
      data: data,
      options: options,
      plugins: [dataLabelPlugin]
    });
  })();

/*********************************************
      twitter画面を開く
 *********************************************/

  {


  }
}
