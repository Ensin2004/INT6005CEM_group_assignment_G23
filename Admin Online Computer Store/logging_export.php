<?php
// logging_export.php
session_set_cookie_params([
  'lifetime' => 0, 'path' => '/', 'secure' => true,
  'httponly' => true, 'samesite' => 'Strict'
]);
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'super_admin') {
  header("HTTP/1.1 403 Forbidden"); echo "Access denied."; exit;
}
require_once "includes/dbh.inc.php";

// Reuse same filters
$actor   = isset($_GET['actor'])   ? trim($_GET['actor'])   : '';
$action  = isset($_GET['action'])  ? trim($_GET['action'])  : '';
$outcome = isset($_GET['outcome']) ? trim($_GET['outcome']) : '';
$etype   = isset($_GET['etype'])   ? trim($_GET['etype'])   : '';
$eid     = isset($_GET['eid'])     ? trim($_GET['eid'])     : '';
$from    = isset($_GET['from'])    ? trim($_GET['from'])    : '';
$to      = isset($_GET['to'])      ? trim($_GET['to'])      : '';
$q       = isset($_GET['q'])       ? trim($_GET['q'])       : '';

$where = []; $params = []; $types="";
if ($actor !== "" && ctype_digit($actor)) { $where[] = "actor_admin_id = ?"; $types.="i"; $params[]=(int)$actor; }
if ($action !== "")   { $where[] = "action = ?";       $types.="s"; $params[]=$action; }
if ($outcome !== "")  { $where[] = "outcome = ?";      $types.="s"; $params[]=$outcome; }
if ($etype !== "")    { $where[] = "entity_type = ?";  $types.="s"; $params[]=$etype; }
if ($eid !== "" && ctype_digit($eid)) { $where[] = "entity_id = ?"; $types.="i"; $params[]=(int)$eid; }
if ($from !== "")     { $where[] = "created_at >= ?";  $types.="s"; $params[]=$from . " 00:00:00"; }
if ($to !== "")       { $where[] = "created_at <= ?";  $types.="s"; $params[]=$to   . " 23:59:59"; }
if ($q !== "")        { $where[] = "summary LIKE ?";   $types.="s"; $params[]="%{$q}%"; }
$whereSql = $where ? ("WHERE " . implode(" AND ", $where)) : "";

$sql = "SELECT created_at, actor_admin_id, actor_role, action, entity_type, entity_id, summary, outcome, ip_address, user_agent FROM audit_logs {$whereSql} ORDER BY created_at DESC, id DESC";
$stmt = $conn->prepare($sql);
if ($types) { $stmt->bind_param($types, ...$params); }
$stmt->execute();
$res = $stmt->get_result();

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=audit_logs.csv');
$out = fopen('php://output', 'w');
fputcsv($out, ['created_at','actor_admin_id','actor_role','action','entity_type','entity_id','summary','outcome','ip_address','user_agent']);
while ($r = $res->fetch_assoc()) {
  fputcsv($out, $r);
}
fclose($out);
