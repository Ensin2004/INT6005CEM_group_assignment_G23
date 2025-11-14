<?php
// logging.php
require_once "includes/dbh.inc.php";
require_once "includes/security.php"; // session + helpers

// Only super_admin can read audit logs
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'super_admin') {
  header("HTTP/1.1 403 Forbidden"); echo "Access denied."; exit;
}

// ---- Filters (GET) ----
$actor   = isset($_GET['actor'])   ? trim($_GET['actor'])   : '';
$action  = isset($_GET['action'])  ? trim($_GET['action'])  : '';
$outcome = isset($_GET['outcome']) ? trim($_GET['outcome']) : '';
$etype   = isset($_GET['etype'])   ? trim($_GET['etype'])   : '';
$eid     = isset($_GET['eid'])     ? trim($_GET['eid'])     : '';
$from    = isset($_GET['from'])    ? trim($_GET['from'])    : '';
$to      = isset($_GET['to'])      ? trim($_GET['to'])      : '';
$q       = isset($_GET['q'])       ? trim($_GET['q'])       : ''; // search in summary

// ---- Pagination ----
$perPage = max(10, min(100, (int)($_GET['per'] ?? 20)));
$page    = max(1, (int)($_GET['page'] ?? 1));
$offset  = ($page - 1) * $perPage;

// ---- Build WHERE dynamically (prepared) ----
$where = [];
$params = [];
$types  = "";

if ($actor !== "" && ctype_digit($actor)) { $where[] = "actor_admin_id = ?"; $types.="i"; $params[]=(int)$actor; }
if ($action !== "")   { $where[] = "action = ?";       $types.="s"; $params[]=$action; }
if ($outcome !== "")  { $where[] = "outcome = ?";      $types.="s"; $params[]=$outcome; }
if ($etype !== "")    { $where[] = "entity_type = ?";  $types.="s"; $params[]=$etype; }
if ($eid !== "" && ctype_digit($eid)) { $where[] = "entity_id = ?"; $types.="i"; $params[]=(int)$eid; }
if ($from !== "")     { $where[] = "created_at >= ?";  $types.="s"; $params[]=$from . " 00:00:00"; }
if ($to !== "")       { $where[] = "created_at <= ?";  $types.="s"; $params[]=$to   . " 23:59:59"; }
if ($q !== "")        { $where[] = "summary LIKE ?";   $types.="s"; $params[]="%{$q}%"; }

$whereSql = $where ? ("WHERE " . implode(" AND ", $where)) : "";

// ---- Count total ----
$sqlCount = "SELECT COUNT(*) AS c FROM audit_logs {$whereSql}";
$stmt = $conn->prepare($sqlCount);
if ($types) { $stmt->bind_param($types, ...$params); }
$stmt->execute();
$total = (int)$stmt->get_result()->fetch_assoc()['c'];
$stmt->close();

// ---- Fetch page ----
$sql = "
  SELECT id, created_at, actor_admin_id, actor_role, action, entity_type, entity_id,
         summary, before_json, after_json, outcome, ip_address, user_agent
  FROM audit_logs
  {$whereSql}
  ORDER BY created_at DESC, id DESC
  LIMIT ? OFFSET ?
";
$stmt = $conn->prepare($sql);
if ($types) {
  $types2 = $types . "ii";
  $stmt->bind_param($types2, ...array_merge($params, [$perPage, $offset]));
} else {
  $stmt->bind_param("ii", $perPage, $offset);
}
$stmt->execute();
$res = $stmt->get_result();
$rows = [];
while ($r = $res->fetch_assoc()) { $rows[] = $r; }
$stmt->close();

// Helper for badge
function badge($text, $kind) {
  $cls = $kind === 'success' ? 'badge-success' : ($kind === 'failure' ? 'badge-failure' : 'badge-neutral');
  return '<span class="badge '.$cls.'">'.htmlspecialchars($text).'</span>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Audit Logs</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="css/logging.css">
</head>
<body class="logs">
  <?php include 'header.php'; ?>

  <!-- keep the site-wide layout: header (top) + footer (bottom) -->
  <main class="page-root">
    <div class="page-wrap">
      <div class="topbar">
        <h1>Audit Logs</h1>
        <div>
          <a class="btn btn-ghost" href="logging_export.php?<?= htmlspecialchars($_SERVER['QUERY_STRING'] ?? '') ?>">Export CSV</a>
        </div>
      </div>

      <!-- Filters card -->
      <div class="card">
        <form class="filters" method="get">
          <div>
            <label for="actor">Actor (Admin ID)</label>
            <input id="actor" name="actor" value="<?= e($actor) ?>" placeholder="e.g. 12" type="text">
          </div>
          <div>
            <label for="action">Action</label>
            <input id="action" name="action" value="<?= e($action) ?>" placeholder="e.g. admin_update" type="text">
          </div>
          <div>
            <label for="outcome">Outcome</label>
            <select id="outcome" name="outcome">
              <option value="">All</option>
              <option value="success" <?= $outcome==='success'?'selected':''; ?>>success</option>
              <option value="failure" <?= $outcome==='failure'?'selected':''; ?>>failure</option>
            </select>
          </div>
          <div>
            <label for="etype">Entity Type</label>
            <input id="etype" name="etype" value="<?= e($etype) ?>" placeholder="e.g. items" type="text">
          </div>
          <div>
            <label for="eid">Entity ID</label>
            <input id="eid" name="eid" value="<?= e($eid) ?>" placeholder="e.g. 55" type="text">
          </div>
          <div>
            <label>&nbsp;</label>
            <div class="actions">
              <button type="button" class="btn" onclick="window.location='logging.php'">Reset</button>
              <button class="btn btn-primary" type="submit">Apply</button>
            </div>
          </div>
          <div>
            <label for="from">From (date)</label>
            <input type="date" id="from" name="from" value="<?= e($from) ?>">
          </div>
          <div>
            <label for="to">To (date)</label>
            <input type="date" id="to" name="to" value="<?= e($to) ?>">
          </div>
          <div>
            <label for="q">Search summary</label>
            <input id="q" name="q" value="<?= e($q) ?>" placeholder="contains…" type="text">
          </div>
          <div>
            <label for="per">Per Page</label>
            <select id="per" name="per">
              <?php foreach ([20,50,100] as $n): ?>
                <option value="<?= $n ?>" <?= $perPage===$n?'selected':''; ?>><?= $n ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </form>
      </div>

      <!-- Table card -->
      <div class="table-wrap mt16">
        <table class="table">
          <thead>
            <tr>
              <th class="nowrap">Time</th>
              <th>Actor</th>
              <th>Action</th>
              <th>Entity</th>
              <th>Summary</th>
              <th>Outcome</th>
              <th class="nowrap">IP</th>
              <th class="nowrap">View</th>
            </tr>
          </thead>
          <tbody>
          <?php if (!$rows): ?>
            <tr><td colspan="8">No logs found.</td></tr>
          <?php else: foreach ($rows as $r): ?>
            <tr>
              <td class="nowrap mono"><?= e($r['created_at']) ?></td>
              <td>
                <div class="mono">#<?= e((string)$r['actor_admin_id'] ?: '-') ?></div>
                <div class="pill"><?= e($r['actor_role'] ?: '-') ?></div>
              </td>
              <td><span class="pill"><?= e($r['action']) ?></span></td>
              <td>
                <div><?= e($r['entity_type'] ?: '-') ?></div>
                <div class="mono">#<?= e((string)$r['entity_id'] ?: '-') ?></div>
              </td>
              <td><?= e($r['summary']) ?></td>
              <td><?= badge($r['outcome'], $r['outcome']) ?></td>
              <td class="mono"><?= e($r['ip_address'] ?: '-') ?></td>
              <td>
                <button
                  class="btn btn-ghost view-btn"
                  data-id="<?= (int)$r['id'] ?>"
                  data-created="<?= e($r['created_at']) ?>"
                  data-actor="<?= e((string)$r['actor_admin_id'] ?: '-') ?>"
                  data-role="<?= e($r['actor_role'] ?: '-') ?>"
                  data-action="<?= e($r['action']) ?>"
                  data-entity="<?= e(($r['entity_type'] ?: '-') . ' #' . ((string)$r['entity_id'] ?: '-')) ?>"
                  data-summary="<?= e($r['summary']) ?>"
                  data-outcome="<?= e($r['outcome']) ?>"
                  data-ip="<?= e($r['ip_address'] ?: '-') ?>"
                  data-ua="<?= e($r['user_agent'] ?: '-') ?>"
                  data-before='<?= e($r['before_json'] ?? '') ?>'
                  data-after='<?= e($r['after_json'] ?? '') ?>'
                >View</button>
              </td>
            </tr>
          <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>

      <?php
        $pages = max(1, (int)ceil($total / $perPage));
        if ($pages > 1):
          $qs = $_GET; unset($qs['page']); $base = http_build_query($qs);
      ?>
        <div class="pagination">
          <?php for ($p=1;$p<=$pages;$p++):
            $href = "logging.php?". $base . ($base ? "&" : "") . "page=".$p;
          ?>
            <?php if ($p == $page): ?>
              <span class="active"><?= $p ?></span>
            <?php else: ?>
              <a href="<?= e($href) ?>"><?= $p ?></a>
            <?php endif; ?>
          <?php endfor; ?>
        </div>
      <?php endif; ?>
    </div>
  </main>

  <!-- Modal -->
  <div class="modal-backdrop" id="modalBackdrop">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="logTitle">
      <div class="topbar">
        <h3 id="logTitle">Audit Entry</h3>
        <button class="btn" id="closeModal">Close</button>
      </div>
      <div id="meta" style="margin-bottom:8px; font-size:0.9rem; color:#444;"></div>
      <div class="diff">
        <div class="col">
          <h4>Before</h4>
          <pre id="beforeBox" class="mono" style="white-space:pre-wrap"></pre>
        </div>
        <div class="col">
          <h4>After</h4>
          <pre id="afterBox" class="mono" style="white-space:pre-wrap"></pre>
        </div>
      </div>
    </div>
  </div>

  <?php include 'footer.php'; ?>

  <script>
    const backdrop = document.getElementById('modalBackdrop');
    const beforeBox = document.getElementById('beforeBox');
    const afterBox  = document.getElementById('afterBox');
    const meta      = document.getElementById('meta');

    function pretty(obj) {
      try {
        if (!obj) return '';
        if (typeof obj === 'string') obj = JSON.parse(obj);
        return JSON.stringify(obj, null, 2);
      } catch(e) { return String(obj); }
    }

    function highlightDiff(beforeObj, afterObj) {
      try {
        const changed = new Set();
        const keys = new Set([...Object.keys(beforeObj||{}), ...Object.keys(afterObj||{})]);
        keys.forEach(k => {
          const a = JSON.stringify((beforeObj||{})[k]);
          const b = JSON.stringify((afterObj||{})[k]);
          if (a !== b) changed.add(k);
        });
        let txt = JSON.stringify(afterObj||{}, null, 2);
        changed.forEach(k => {
          const re = new RegExp('"(?:' + k.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')"\\s*:', 'g');
          txt = txt.replace(re, (m)=>'<span class="changed">'+m+'</span>');
        });
        return txt;
      } catch(e) { return pretty(afterObj); }
    }

    document.querySelectorAll('.view-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const data = btn.dataset;
        const before = data.before ? JSON.parse(data.before) : null;
        const after  = data.after  ? JSON.parse(data.after)  : null;

        meta.innerHTML = `
          <div><b>Time:</b> ${data.created}</div>
          <div><b>Actor:</b> #${data.actor} (${data.role})</div>
          <div><b>Action:</b> ${data.action} | <b>Entity:</b> ${data.entity}</div>
          <div><b>Outcome:</b> ${data.outcome} | <b>IP:</b> ${data.ip}</div>
          <div style="word-break:break-all"><b>UA:</b> ${data.ua}</div>
          <div style="margin-top:6px"><b>Summary:</b> ${data.summary}</div>
        `;

        beforeBox.textContent = pretty(before);
        afterBox.innerHTML    = highlightDiff(before, after);
        backdrop.style.display = 'flex';
      });
    });

    function backDropClose(){ backdrop.style.display='none'; }
    document.getElementById('closeModal').addEventListener('click', backDropClose);
    backdrop.addEventListener('click', (e)=> { if (e.target === backdrop) backDropClose(); });
  </script>
</body>
</html>
