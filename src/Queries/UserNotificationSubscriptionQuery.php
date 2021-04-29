<?php

namespace Sfneal\Users\Queries;

use Illuminate\Database\Eloquent\Collection;
use Sfneal\Caching\Traits\Cacheable;
use Sfneal\Helpers\Laravel\LaravelHelpers;
use Sfneal\PostOffice\Notifications\AbstractNotification;
use Sfneal\Queries\Query;
use Sfneal\Users\Builders\UserBuilder;
use Sfneal\Users\Builders\UserNotificationBuilder;
use Sfneal\Users\Models\User;

class UserNotificationSubscriptionQuery extends Query
{
    // todo: improve type hinting
    use Cacheable;

    /**
     * @var string
     */
    private $notification;

    /**
     * UserNotificationSubscriptionQuery constructor.
     *
     * @param AbstractNotification $notification
     */
    public function __construct(AbstractNotification $notification)
    {
        $this->notification = LaravelHelpers::getClassName($notification);
    }

    /**
     * Retrieve a Query builder.
     *
     * @return UserBuilder
     */
    protected function builder(): UserBuilder
    {
        return User::query();
    }

    /**
     * Retrieve a Collection of User's that are subscribed to a Notification.
     *
     *  - only return user 'Stephen Neal' if environment is not 'production'
     *
     * @return Collection
     */
    public function execute()
    {
        // Production environment
        if (env('APP_ENV') == 'production') {
            return $this->builder()
                ->whereHas('notificationSubscriptions', function (UserNotificationBuilder $builder) {
                    $builder->whereType($this->notification);
                })
                ->get();
        }

        // Development environment
        else {
            return $this->builder()
                ->whereUser(38)
                ->get();
        }
    }

    /**
     * Retrieve the Query cache key.
     *
     * @return string
     */
    public function cacheKey(): string
    {
        return "user:notification:subscription#{$this->notification}";
    }
}
