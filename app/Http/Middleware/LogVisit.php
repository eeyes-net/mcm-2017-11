<?php

namespace App\Http\Middleware;

use App\VisitLog;
use Closure;
use Illuminate\Http\Request;

class LogVisit
{
    public function handle(Request $request, Closure $next, $log_response_body = '')
    {
        if (!config('mcm.log_visit')) {
            return $next($request);
        }

        $visit_log = new VisitLog();
        /** @var \App\User|null $user */
        $user = $request->user();
        if ($user) {
            $visit_log->user_id = $user->id;
            $visit_log->user_info = join(',', [
                $user->username,
                $user->stu_id,
                $user->name,
            ]);
        } else {
            $visit_log->user_id = 0;
            $visit_log->user_info = '';
        }
        $visit_log->path = $request->path();
        $visit_log->method = $request->method();
        $visit_log->ip = $request->ip();
        $visit_log->user_agent = mb_substr($request->userAgent(), 0, 1024);
        $visit_log->query = mb_substr((string)$request->getQueryString(), 0, 1024);
        $visit_log->body = mb_substr((string)$request->getContent(), 0, 4096);
        $visit_log->response_code = 0;
        $visit_log->response_length = 0;
        $visit_log->response_body = '';
        $visit_log->save();

        /** @var \Illuminate\Http\Response $response */
        $response = $next($request);

        $visit_log->response_code = $response->getStatusCode();
        $response_content = $response->getContent();
        $visit_log->response_length = strlen($response_content);
        if ($log_response_body) {
            $visit_log->response_body = mb_substr($response_content, 0, 4096);
        }
        $visit_log->save();

        return $response;
    }
}
