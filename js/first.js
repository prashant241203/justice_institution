
      const cases = [{
        id: 'C001',
        title: 'Land Dispute A',
        date: '2025-11-15',
        status: 'Open'
      }, {
        id: 'C002',
        title: 'Environmental Case B',
        date: '2025-11-16',
        status: 'Pending'
      }];
      const hearings = [{
        id: 'H001',
        caseId: 'C001',
        date: '2025-11-22',
        court: 'Court 1'
      }, {
        id: 'H002',
        caseId: 'C002',
        date: '2025-11-23',
        court: 'Court 2'
      }];
      const judgements = [{
        id: 'J001',
        caseId: 'C002',
        date: '2025-11-24',
        outcome: 'Settlement'
      }];

      function renderCases() {
        const tbody = document.getElementById('caseTableBody');
        tbody.innerHTML = '';
        cases.forEach(c => {
          const tr = document.createElement('tr');
          tr.innerHTML = `
<td>${c.id}</td>
<td>${c.title}</td>
<td>${c.date}</td>
<td>${c.status}</td>`;
          tbody.appendChild(tr);
        });
        document.getElementById('totalCases').textContent = cases.length;
      }

      function renderHearings() {
        const tbody = document.getElementById('hearingTableBody');
        tbody.innerHTML = '';
        hearings.forEach(h => {
          const tr = document.createElement('tr');
          tr.innerHTML = `
<td>${h.id}</td>
<td>${h.caseId}</td>
<td>${h.date}</td>
<td>${h.court}</td>`;
          tbody.appendChild(tr);
        });
        document.getElementById('totalHearings').textContent = hearings.length;
      }

      function renderJudgements() {
        const tbody = document.getElementById('judgementTableBody');
        tbody.innerHTML = '';
        judgements.forEach(j => {
          const tr = document.createElement('tr');
          tr.innerHTML = `
<td>${j.id}</td>
<td>${j.caseId}</td>
<td>${j.date}</td>
<td>${j.outcome}</td>`;
          tbody.appendChild(tr);
        });
        document.getElementById('totalJudgements').textContent = judgements.length;
      }

      // function saveCaseModal(e) {
      //   e.preventDefault();
      //   const id = 'C' + String(Math.max(0, ...cases.map(x => parseInt(x.id.slice(1)))) + 1).padStart(3, '0');
      //   const rec = {
      //     id: id,
      //     title: document.getElementById('cTitle').value,
      //     date: document.getElementById('cDate').value,
      //     status: document.getElementById('cStatus').value
      //   };
      //   cases.push(rec);
      //   renderAll();
      //   bootstrap.Modal.getInstance(document.getElementById('addCase')).hide();
      //   e.target.reset();
      // }

      function renderAll() {
        renderCases();
        renderHearings();
        renderJudgements();
        updateCharts();
      }

      function openModal(id) {
        const m = new bootstrap.Modal(document.getElementById(id));
        m.show();
      }

      function runPatternCheck() {
        alert('Pattern detection run (mock)');
        updateCharts();
      }
      let caseTrendChart, patternChart;

      function updateCharts(){
        const ctx = document.getElementById('caseTrendChart').getContext('2d');
        if(!caseTrendChart){
          caseTrendChart = new Chart(ctx,{
            type:'bar',
            data:{
              labels:cases.map(c=>c.title),
              datasets:[{
                label:'Cases',
                data:cases.map(c=>1),
                backgroundColor:'#0078d4'
              }]
            }
          });
        } else {
          caseTrendChart.update();
        }

        const ctx2 = document.getElementById('patternChart').getContext('2d');
        if(!patternChart){
          patternChart = new Chart(ctx2,{
            type:'line',
            data:{
              labels:cases.map(c=>c.title),
              datasets:[{
                label:'Pattern Flags',
                data:cases.map(c=>Math.floor(Math.random()*2)),
                borderColor:'#e81123',
                tension:0.3
              }]
            }
          });
        } else {
          patternChart.update();
        }

        document.getElementById('patternFlags').textContent =
          cases.reduce((a)=>a+Math.floor(Math.random()*2),0);
      }


        function exportCases() {
          const csv = ['id,title,date,status', ...cases.map(c => `${c.id},${c.title},${c.date},${c.status}`)].join('\n');
          const blob = new Blob([csv], {
            type: 'text/csv'
          });
          const a = document.createElement('a');
          a.href = URL.createObjectURL(blob);
          a.download = 'cases_mock.csv';
          a.click();
        }
        document.addEventListener('DOMContentLoaded', () => {
          renderAll();
        });
        


