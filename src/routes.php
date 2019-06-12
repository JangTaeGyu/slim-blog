<?php

use Slim\App;
use App\Controllers\{
    MemberController,
    AuthController,
    AdminMainController,
    AdminCategoryController,
    AdminNoticeController,
    AdminCommentController,
    AdminGuestBookController,
    AdminUserController
};
use App\Middlewares\{
    AuthMiddleware,
    UnAuthMiddleware,
    PaginationMiddleware
};

return function (App $app) {
    $app->get('/', \App\Controllers\BlogController::class . ':index')->setName('blog.main');

    // 블로그 > 가입하기
    $app->group('/member', function (App $app) {
        $app->get('/join', MemberController::class . ':join')->setName('blog.member.join');
        $app->post('/join', MemberController::class . ':joinAction')->setName('blog.member.join.action');
    })->add(UnAuthMiddleware::class);

    // 블로그 > 로그인
    $app->group('/auth', function (App $app) {
        $app->get('/login', AuthController::class . ':login')->setName('blog.auth.login');
        $app->post('/login', AuthController::class . ':loginAction')->setName('blog.auth.login.action');
    })->add(UnAuthMiddleware::class);

    // 로그아웃
    $app->get('/logout', AuthController::class . ':logout')->setName('blog.auth.logout');

    $app->group('/admin', function (App $app) {

        // 관리자 > 블로그 관리 홈
        $app->get('/main', AdminMainController::class . ':main')->setName('blog.admin.main');

        // 관리자 > 콘텐츠 > 카테고리 관리
        $app->get('/categories', AdminCategoryController::class . ':index')->setName('blog.admin.category.index');
        $app->post('/categories', AdminCategoryController::class . ':store')->setName('blog.admin.category.store');
        $app->put('/categories/{category_id:[0-9]+}', AdminCategoryController::class . ':update')->setName('blog.admin.category.update');
        $app->delete('/categories/{category_id:[0-9]+}', AdminCategoryController::class . ':destroy')->setName('blog.admin.category.destroy');

        // 관리자 > 콘텐츠 > 공지 관리
        $app->get('/notices', AdminNoticeController::class . ':index')->add(PaginationMiddleware::class)->setName('blog.admin.notice.index');
        $app->get('/notices/create', AdminNoticeController::class . ':create')->setName('blog.admin.notice.create');
        $app->get('/notices/{notice_id:[0-9]+}/edit', AdminNoticeController::class . ':edit')->setName('blog.admin.notice.edit');
        $app->post('/notices', AdminNoticeController::class . ':store')->setName('blog.admin.notice.store');
        $app->put('/notices/{notice_id:[0-9]+}', AdminNoticeController::class . ':update')->setName('blog.admin.notice.update');
        $app->put('/notices/{notice_id:[0-9]+}/approved', AdminNoticeController::class . ':updateApproved')->setName('blog.admin.notice.update.approved');
        $app->delete('/notices/{notice_id:[0-9]+}', AdminNoticeController::class . ':destroy')->setName('blog.admin.notice.destroy');

        // 관리자 > 댓글.방명록 > 댓글 관리
        $app->get('/comments', AdminCommentController::class . ':index')->add(PaginationMiddleware::class)->setName('blog.admin.comment.index');
        $app->get('/comments/trash', AdminCommentController::class . ':index')->add(PaginationMiddleware::class)->setName('blog.admin.comment.trash');
        $app->post('/comments', AdminCommentController::class . ':store')->setName('blog.admin.comment.store');
        $app->put('/comments/{comment_id:[0-9]+}', AdminCommentController::class . ':update')->setName('blog.admin.comment.update');
        $app->delete('/comments/{comment_id:[0-9]+}', AdminCommentController::class . ':destroy')->setName('blog.admin.comment.destroy');

        // 관리자 > 댓글.방명록 > 방명록 관리
        $app->get('/guestbooks', AdminGuestBookController::class . ':index')->add(PaginationMiddleware::class)->setName('blog.admin.guestbook.index');
        $app->get('/guestbooks/trash', AdminGuestBookController::class . ':index')->add(PaginationMiddleware::class)->setName('blog.admin.guestbook.trash');
        $app->post('/guestbooks', AdminGuestBookController::class . ':store')->setName('blog.admin.guestbook.store');
        $app->put('/guestbooks/{guestbook_id:[0-9]+}', AdminGuestBookController::class . ':update')->setName('blog.admin.guestbook.update');
        $app->delete('/guestbooks/{guestbook_id:[0-9]+}', AdminGuestBookController::class . ':destroy')->setName('blog.admin.guestbook.destroy');

        // 관리자 > 회원 관리
        $app->get('/users', AdminUserController::class . ':index')->add(PaginationMiddleware::class)->setName('blog.admin.user.index');
        $app->get('/users/{user_id:[0-9]+}/edit', AdminUserController::class . ':edit')->setName('blog.admin.user.edit');
        $app->put('/users/{user_id:[0-9]+}', AdminUserController::class . ':update')->setName('blog.admin.user.update');
        $app->put('/users/{user_id:[0-9]+}/approved', AdminUserController::class . ':updateApproved')->setName('blog.admin.user.update.approved');
        $app->delete('/users/{user_id:[0-9]+}', AdminUserController::class . ':destroy')->setName('blog.admin.user.destroy');
    })->add(AuthMiddleware::class);
};
