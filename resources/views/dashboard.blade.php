<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>LG Electronics — Dashboard Planta A</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Barlow:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          fontFamily: {
            sans: ['Barlow', 'sans-serif'],
            mono: ['DM Mono', 'monospace'],
          }
        }
      }
    }
  </script>

  <style>
    :root {
      --bg:         #f2f2f2;
      --surface:    #ffffff;
      --surface2:   #ebebeb;
      --border:     rgba(0,0,0,0.08);
      --border2:    rgba(0,0,0,0.15);
      --text:       #1a1a1a;
      --muted:      #666666;
      --faint:      #999999;
      --prog-bg:    rgba(0,0,0,0.08);
      --row-hover:  rgba(165,0,52,0.04);
      --chart-grid: rgba(0,0,0,0.06);
      --chart-tick: rgba(0,0,0,0.45);
      --tt-bg:      rgba(20,20,20,0.95);
      --tt-txt:     #ffffff;
    }
    html.dark {
      --bg:         #0d0d0f;
      --surface:    #161618;
      --surface2:   #1e1e21;
      --border:     rgba(255,255,255,0.07);
      --border2:    rgba(255,255,255,0.13);
      --text:       #f0f0f0;
      --muted:      #9a9a9a;
      --faint:      #5a5a5a;
      --prog-bg:    rgba(255,255,255,0.08);
      --row-hover:  rgba(165,0,52,0.10);
      --chart-grid: rgba(255,255,255,0.05);
      --chart-tick: rgba(255,255,255,0.35);
      --tt-bg:      rgba(8,8,10,0.97);
      --tt-txt:     #f0f0f0;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      background: var(--bg);
      color: var(--text);
      font-family: 'Barlow', sans-serif;
      min-height: 100vh;
      transition: background 0.3s, color 0.3s;
    }

    .card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 14px;
      transition: background 0.3s, border-color 0.3s;
    }
    .card-sm { border-radius: 12px; }

    .filter-btn {
      background: transparent;
      border: 1px solid var(--border2);
      color: var(--muted);
      border-radius: 8px;
      padding: 7px 15px;
      font-size: 13px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.16s ease;
      font-family: 'Barlow', sans-serif;
    }
    .filter-btn:hover { background: var(--surface2); color: var(--text); }
    .filter-btn.active { background: #A50034; color: #fff; border-color: #A50034; }

    .prog-track { background: var(--prog-bg); border-radius: 999px; height: 7px; overflow: hidden; }
    .prog-fill  { height: 100%; border-radius: 999px; transition: width 0.85s cubic-bezier(.4,0,.2,1); }

    table.dt { width: 100%; border-collapse: collapse; }
    table.dt thead th {
      font-size: 10.5px;
      font-family: 'DM Mono', monospace;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: var(--faint);
      padding: 10px 20px;
      text-align: right;
      font-weight: 400;
      white-space: nowrap;
    }
    table.dt thead th:first-child { text-align: left; }
    table.dt tbody td {
      padding: 13px 20px;
      text-align: right;
      font-size: 13px;
      border-top: 1px solid var(--border);
      color: var(--text);
      transition: background 0.1s;
      white-space: nowrap;
    }
    table.dt tbody td:first-child { text-align: left; }
    table.dt tbody tr:hover td { background: var(--row-hover); }

    .toggle-wrap { display:flex; align-items:center; gap:8px; }
    .toggle-track {
      width: 42px; height: 23px;
      border-radius: 999px;
      background: var(--surface2);
      border: 1px solid var(--border2);
      position: relative;
      cursor: pointer;
      transition: background 0.25s, border-color 0.25s;
    }
    html.dark .toggle-track { background: #A50034; border-color: #A50034; }
    .toggle-thumb {
      position: absolute;
      top: 3px; left: 3px;
      width: 15px; height: 15px;
      border-radius: 50%;
      background: var(--muted);
      transition: left 0.22s cubic-bezier(.4,0,.2,1), background 0.22s;
    }
    html.dark .toggle-thumb { left: 22px; background: #fff; }

    @keyframes fadeUp {
      from { opacity:0; transform:translateY(14px); }
      to   { opacity:1; transform:translateY(0); }
    }
    .fu  { animation: fadeUp 0.4s ease both; }
    .d1  { animation-delay:.04s; } .d2 { animation-delay:.08s; }
    .d3  { animation-delay:.12s; } .d4 { animation-delay:.16s; }
    .d5  { animation-delay:.20s; } .d6 { animation-delay:.24s; }

    @keyframes livepulse {
      0%,100% { box-shadow:0 0 0 0 rgba(34,197,94,0.55); }
      50%      { box-shadow:0 0 0 5px rgba(34,197,94,0); }
    }
    .live-dot { animation: livepulse 2s ease-in-out infinite; }

    ::-webkit-scrollbar { width:5px; height:5px; }
    ::-webkit-scrollbar-track { background:transparent; }
    ::-webkit-scrollbar-thumb { background:var(--border2); border-radius:3px; }
  </style>
</head>

<body>

  <div style="height:4px; background:linear-gradient(90deg,#A50034 0%,#d4003f 55%,#ff4060 100%);"></div>

  <nav style="background:var(--surface); border-bottom:1px solid var(--border); transition:background 0.3s,border-color 0.3s;">
    <div style="max-width:1200px; margin:0 auto; padding:0 24px; height:56px; display:flex; align-items:center; justify-content:space-between;">

      <div style="display:flex; align-items:center; gap:12px;">
        <img src="{{ asset('logo-lg-100-44.svg') }}" alt="LG Electronics" style="height:44px;width:auto;" />
        <div>
          <div style="font-weight:700;font-size:14px;color:var(--text);line-height:1;">LG Electronics</div>
          <div style="font-size:11px;color:var(--faint);font-family:'DM Mono',monospace;margin-top:1px;">Planta A · Manaus</div>
        </div>
      </div>

      <div style="display:flex;align-items:center;gap:16px;">
        <span style="display:flex;align-items:center;gap:6px;font-size:12px;font-family:'DM Mono',monospace;color:#22c55e;">
          <span class="live-dot" style="width:8px;height:8px;border-radius:50%;background:#22c55e;display:inline-block;"></span>
          Ao vivo
        </span>

        <div class="toggle-wrap">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="color:var(--muted);">
            <circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/>
            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
            <line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/>
            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
          </svg>
          <div class="toggle-track" id="themeToggle">
            <div class="toggle-thumb"></div>
          </div>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="color:var(--muted);">
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
          </svg>
        </div>

        <form method="POST" action="{{ route('logout') }}" style="margin-left:6px;">
          @csrf
          <button type="submit" class="filter-btn" style="padding:7px 12px;">Sair</button>
        </form>
      </div>
    </div>
  </nav>

  <main style="max-width:1200px; margin:0 auto; padding:32px 24px;">

    <div class="fu" style="display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;gap:20px;margin-bottom:28px;">
      <div>
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
          <div style="width:3px;height:20px;background:#A50034;border-radius:2px;"></div>
          <span style="font-size:11px;font-weight:700;color:#A50034;font-family:'DM Mono',monospace;text-transform:uppercase;letter-spacing:0.1em;">Eficiência de Produção</span>
          <span title="Eficiência (%) = (produzida - defeitos) / produzida * 100" style="display:inline-flex;align-items:center;justify-content:center;width:18px;height:18px;border-radius:999px;border:1px solid rgba(165,0,52,0.35);color:#A50034;font-family:'DM Mono',monospace;font-size:11px;cursor:help;">i</span>
        </div>
        <h1 style="font-size:26px;font-weight:700;letter-spacing:-0.02em;color:var(--text);">Dashboard — Janeiro 2026</h1>
        <p style="font-size:13px;color:var(--muted);margin-top:4px;font-family:'DM Mono',monospace;">{{ $userEmail ?? 'test@test.com' }}</p>
      </div>

      <div id="filterGroup" style="display:flex;flex-wrap:wrap;gap:8px;">
        <button class="filter-btn active" data-filter="all">Todas as linhas</button>
        <button class="filter-btn" data-filter="geladeira">Geladeira</button>
        <button class="filter-btn" data-filter="maquina">Máq. de Lavar</button>
        <button class="filter-btn" data-filter="tv">TV</button>
        <button class="filter-btn" data-filter="arcondicionado">Ar-Condicionado</button>
      </div>
    </div>

    <div id="kpiGrid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:16px;"></div>

    <div style="display:grid;grid-template-columns:2fr 3fr;gap:12px;margin-bottom:16px;">

      <div class="card fu d3" style="padding:24px;">
        <p style="font-size:10.5px;font-family:'DM Mono',monospace;text-transform:uppercase;letter-spacing:0.08em;color:var(--faint);margin-bottom:20px;">Eficiência por linha</p>
        <div id="effBars" style="display:flex;flex-direction:column;gap:18px;"></div>
      </div>

      <div class="card fu d4" style="padding:24px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
          <p style="font-size:10.5px;font-family:'DM Mono',monospace;text-transform:uppercase;letter-spacing:0.08em;color:var(--faint);">Produzida vs Defeitos</p>
          <div style="display:flex;gap:16px;">
            <span style="display:flex;align-items:center;gap:5px;font-size:11px;font-family:'DM Mono',monospace;color:var(--muted);">
              <span style="width:10px;height:10px;border-radius:3px;background:#A50034;display:inline-block;"></span>Produzida
            </span>
            <span style="display:flex;align-items:center;gap:5px;font-size:11px;font-family:'DM Mono',monospace;color:var(--muted);">
              <span style="width:10px;height:10px;border-radius:3px;background:#58595B;display:inline-block;"></span>Defeitos
            </span>
          </div>
        </div>
        <div style="position:relative;height:230px;">
          <canvas id="barChart"></canvas>
        </div>
      </div>
    </div>

    <div class="card fu d5" style="overflow:hidden;">
      <div style="padding:16px 20px;border-bottom:1px solid var(--border);">
        <p style="font-size:10.5px;font-family:'DM Mono',monospace;text-transform:uppercase;letter-spacing:0.08em;color:var(--faint);">Detalhamento por linha de produção</p>
      </div>
      <div style="overflow-x:auto;">
        <table class="dt">
          <thead>
            <tr>
              <th style="text-align:left;">Linha</th>
              <th>Qtd. Produzida</th>
              <th>Defeitos</th>
              <th>Taxa Defeito</th>
              <th>Eficiência</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody id="tableBody"></tbody>
        </table>
      </div>
    </div>

    <p class="fu d6" style="text-align:center;font-size:11px;font-family:'DM Mono',monospace;color:var(--faint);margin-top:28px;">
      LG Electronics · Planta A · Dashboard de Produção · jan/2026
    </p>
  </main>

<script>
const allData = @json($data ?? []);

const colors = {
  geladeira: '#A50034',
  maquina: '#c4003a',
  tv: '#58595B',
  arcondicionado: '#7d0027',
};

allData.forEach(r => { r.color = colors[r.id] || '#A50034'; });

let chartInst = null;
let activeFilter = 'all';

const eff    = r => ((r.produzida - r.defeitos) / r.produzida * 100);
const fmt    = n => n.toLocaleString('pt-BR');
const isDark = () => document.documentElement.classList.contains('dark');

function getFiltered() {
  return activeFilter === 'all' ? allData : allData.filter(r => r.id === activeFilter);
}

function statusBadge(e) {
  if (e >= 95) return { label:'Excelente', light:{ bg:'#dcfce7',color:'#14532d' }, dark:{ bg:'rgba(22,163,74,0.18)',color:'#4ade80' } };
  if (e >= 92) return { label:'Bom',       light:{ bg:'#dbeafe',color:'#1e3a8a' }, dark:{ bg:'rgba(37,99,235,0.18)',color:'#60a5fa' } };
  if (e >= 88) return { label:'Regular',   light:{ bg:'#fef3c7',color:'#78350f' }, dark:{ bg:'rgba(217,119,6,0.18)', color:'#fbbf24' } };
  return             { label:'Atenção',   light:{ bg:'#fee2e2',color:'#7f1d1d' }, dark:{ bg:'rgba(220,38,38,0.18)',  color:'#f87171' } };
}

function cv(name) {
  return getComputedStyle(document.documentElement).getPropertyValue(name).trim();
}

function renderKPIs(data) {
  const totalProd = data.reduce((s,r)=>s+r.produzida,0);
  const totalDef  = data.reduce((s,r)=>s+r.defeitos,0);
  const avgEff    = data.reduce((s,r)=>s+eff(r),0)/data.length;
  const defRate   = (totalDef/totalProd*100);
  const best      = data.reduce((b,r)=>eff(r)>eff(b)?r:b,data[0]);

  const kpis = [
    { label:'Total produzido',   value:fmt(totalProd),          sub:'unidades · jan/2026',                              vc:'var(--text)'  },
    { label:'Total de defeitos', value:fmt(totalDef),           sub:`taxa de ${defRate.toFixed(1)}%`,                   vc:'#dc2626'      },
    { label:'Eficiência média',  value:avgEff.toFixed(1)+'%',   sub:`${data.length} linha${data.length>1?'s':''} ativa${data.length>1?'s':''}`, vc:'#16a34a' },
    { label:'Melhor linha',      value:best.label,              sub:`${eff(best).toFixed(1)}% eficiência`,              vc:'#A50034'      },
  ];

  document.getElementById('kpiGrid').innerHTML = kpis.map((k,i)=>`
    <div class="card card-sm fu d${i+1}" style="padding:18px 20px;">
      <p style="font-size:10px;font-family:'DM Mono',monospace;text-transform:uppercase;letter-spacing:0.08em;color:var(--faint);margin-bottom:10px;">${k.label}</p>
      <p style="font-size:22px;font-weight:700;font-family:'DM Mono',monospace;color:${k.vc};line-height:1;">${k.value}</p>
      <p style="font-size:11px;color:var(--faint);margin-top:6px;">${k.sub}</p>
    </div>
  `).join('');
}

function renderEffBars(data) {
  document.getElementById('effBars').innerHTML = data.map(r=>{
    const e = eff(r);
    return `
      <div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
          <span style="display:flex;align-items:center;gap:8px;font-size:13px;font-weight:500;color:var(--text);">
            <span style="width:10px;height:10px;border-radius:50%;background:${r.color};display:inline-block;flex-shrink:0;"></span>
            ${r.label}
          </span>
          <span style="font-family:'DM Mono',monospace;font-size:13px;font-weight:500;color:${r.color};">${e.toFixed(1)}%</span>
        </div>
        <div class="prog-track">
          <div class="prog-fill" style="width:${e.toFixed(2)}%;background:${r.color};"></div>
        </div>
      </div>
    `;
  }).join('');
}

function renderBarChart(data) {
  const canvas = document.getElementById('barChart');
  if (chartInst) chartInst.destroy();

  chartInst = new Chart(canvas,{
    type:'bar',
    data:{
      labels: data.map(r=>r.label),
      datasets:[
        { label:'Produzida', data:data.map(r=>r.produzida), backgroundColor:'#A50034', borderRadius:5, borderSkipped:false },
        { label:'Defeitos',  data:data.map(r=>r.defeitos),  backgroundColor:'#58595B', borderRadius:5, borderSkipped:false },
      ]
    },
    options:{
      responsive:true,
      maintainAspectRatio:false,
      plugins:{
        legend:{ display:false },
        tooltip:{
          backgroundColor: cv('--tt-bg'),
          borderColor:'rgba(165,0,52,0.4)',
          borderWidth:1,
          titleColor: cv('--tt-txt'),
          bodyColor:  cv('--tt-txt'),
          padding:10,
          callbacks:{ label: ctx=>` ${ctx.dataset.label}: ${fmt(ctx.raw)}` }
        }
      },
      scales:{
        x:{ grid:{display:false}, ticks:{color:cv('--chart-tick'),font:{size:11,family:'DM Mono'}}, border:{color:'transparent'} },
        y:{ grid:{color:cv('--chart-grid')}, ticks:{color:cv('--chart-tick'),font:{size:11,family:'DM Mono'},callback:v=>v>=1000?(v/1000).toFixed(0)+'k':v}, border:{color:'transparent'} }
      }
    }
  });
}

function renderTable(data) {
  document.getElementById('tableBody').innerHTML = data.map(r=>{
    const e      = eff(r);
    const defPct = (r.defeitos/r.produzida*100).toFixed(1);
    const s      = statusBadge(e);
    const sc     = isDark() ? s.dark : s.light;
    return `
      <tr>
        <td>
          <div style="display:flex;align-items:center;gap:10px;">
            <span style="width:11px;height:11px;border-radius:50%;background:${r.color};display:inline-block;flex-shrink:0;"></span>
            <span style="font-weight:600;font-size:13px;color:var(--text);">${r.label}</span>
          </div>
        </td>
        <td><span style="font-family:'DM Mono',monospace;">${fmt(r.produzida)}</span></td>
        <td><span style="font-family:'DM Mono',monospace;color:#dc2626;">${fmt(r.defeitos)}</span></td>
        <td><span style="font-family:'DM Mono',monospace;color:var(--muted);">${defPct}%</span></td>
        <td style="min-width:180px;">
          <div style="display:flex;align-items:center;gap:10px;">
            <div class="prog-track" style="flex:1;">
              <div class="prog-fill" style="width:${e.toFixed(2)}%;background:${r.color};"></div>
            </div>
            <span style="font-family:'DM Mono',monospace;font-size:12px;font-weight:600;color:${r.color};min-width:42px;text-align:right;">${e.toFixed(1)}%</span>
          </div>
        </td>
        <td>
          <span style="display:inline-block;padding:3px 12px;border-radius:999px;font-size:11px;font-weight:600;background:${sc.bg};color:${sc.color};">${s.label}</span>
        </td>
      </tr>
    `;
  }).join('');
}

function render() {
  const data = getFiltered();

  renderKPIs(data);
  renderEffBars(data);
  renderBarChart(data);
  renderTable(data);
}

document.getElementById('filterGroup').addEventListener('click', e=>{
  const btn = e.target.closest('.filter-btn');
  if (!btn) return;

  document.querySelectorAll('#filterGroup .filter-btn').forEach(b=>b.classList.remove('active'));
  btn.classList.add('active');

  activeFilter = btn.dataset.filter;
  render();
});

document.getElementById('themeToggle').addEventListener('click', ()=>{
  document.documentElement.classList.toggle('dark');
  render();
});

function applyResponsive() {
  const kpiGrid = document.getElementById('kpiGrid');
  if (window.innerWidth < 600) {
    kpiGrid.style.gridTemplateColumns = 'repeat(2,1fr)';
  } else {
    kpiGrid.style.gridTemplateColumns = 'repeat(4,1fr)';
  }
  const chartGrid = kpiGrid.nextElementSibling;
  if (window.innerWidth < 900) {
    chartGrid.style.gridTemplateColumns = '1fr';
  } else {
    chartGrid.style.gridTemplateColumns = '2fr 3fr';
  }
}

window.addEventListener('resize', applyResponsive);
applyResponsive();

render();
</script>
</body>
</html>
