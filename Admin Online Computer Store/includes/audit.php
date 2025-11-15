<?php
function audit_log(
    mysqli $conn,
    ?int $actor_admin_id,
    ?string $actor_role,         // 'manager' | 'super_admin' | null (for login_failure)
    string $action,              // e.g. 'item_update'
    ?string $entity_type,        // e.g. 'items'
    ?int $entity_id,             // target id or null
    string $summary,             // one-line human text
    array $before = null,        // assoc array (selected fields) BEFORE
    array $after  = null,        // assoc array (selected fields) AFTER
    string $outcome = 'success'  // 'success' | 'failure'
) : void {
    // Scrub sensitive keys if someone passes them accidentally
    $scrub = function($arr) {
        if ($arr === null) return null;
        $blocked = ['pwd','admin_pwd','password','token','secret','screenshot'];
        foreach ($blocked as $k) {
            if (array_key_exists($k, $arr)) $arr[$k] = '[REDACTED]';
        }
        return $arr;
    };
    $before = $scrub($before);
    $after  = $scrub($after);

    $ip = $_SERVER['REMOTE_ADDR'] ?? null;
    $ua = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);

    $stmt = $conn->prepare("
        INSERT INTO audit_logs
        (actor_admin_id, actor_role, action, entity_type, entity_id, summary,
         before_json, after_json, outcome, ip_address, user_agent)
        VALUES (?,?,?,?,?,?,?,?,?,?,?)
    ");
    $beforeJson = $before ? json_encode($before, JSON_UNESCAPED_UNICODE) : null;
    $afterJson  = $after  ? json_encode($after,  JSON_UNESCAPED_UNICODE) : null;

    $stmt->bind_param(
        "isssissssss",
        $actor_admin_id,
        $actor_role,
        $action,
        $entity_type,
        $entity_id,
        $summary,
        $beforeJson,
        $afterJson,
        $outcome,
        $ip,
        $ua
    );
    $stmt->execute();
    $stmt->close();
}
