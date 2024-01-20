<?php

namespace App\Services\User;

use App\Models\User;
use App\Traits\GlobalTrait;
use Illuminate\Support\Facades\DB;

class UserService
{
    use GlobalTrait;
    public function getUsers()
    {
        $users = User::query();
        $users = $this->checkRule($users);
        $request = request()->all();
        if ($request) {
            if (array_key_exists('active', $request)) {
                $users = $this->getUsersByStatus($users, $request['active']);
            }
            $users = $this->usersFilter($users, $request);
            // dd(request()->all());
        }
        return  $users;
    }
    public function getUserOrders($userId)
    {
        return  User::select('id', 'name', 'img', 'address', 'phone')->where("id", $userId)
            ->where('type', 'purchase')
            ->with(['orders' => function ($q) {
                $q->select('id', 'ref', 'total_without_discount', 'total', 'user_id', 'customer_id')
                    ->with(['customer' => function ($q) {
                        $q->select('id', 'name');
                    }]);
            }]);
    }

    public function getUserOrdersReturned($userId)
    {
        return User::select('id', 'name', 'img', 'address', 'phone')->where("id", $userId)
            ->where('type', 'returned')
            ->with(['orders' => function ($q) {
                $q->select('id', 'ref', 'total_without_discount', 'total', 'user_id', 'customer_id')
                    ->with(['customer' => function ($q) {
                        $q->select('id', 'name');
                    }]);
            }]);
    }

    public function getUserPurchases($userId)
    {
        return User::select('id', 'name', 'img', 'address', 'phone')->where("id", $userId)
            ->where('type', 'purchase')
            ->with(['purchases' => function ($q) {
                $q->select('id', 'ref', 'total_without_discount', 'total', 'user_id', 'supplier_id')
                    ->with(['supplier' => function ($q) {
                        $q->select('id', 'name');
                    }]);
            }]);
    }

    public function getUserPurchasesReturned($userId)
    {
        return User::select('id', 'name', 'img', 'address', 'phone')->where("id", $userId)
            ->where('type', 'returned')
            ->with(['purchases' => function ($q) {
                $q->select('id', 'ref', 'total_without_discount', 'total', 'user_id', 'supplier_id')
                    ->with(['supplier' => function ($q) {
                        $q->select('id', 'name');
                    }]);
            }]);
    }

    public function getUserStores($userId)
    {
        return User::select('id', 'name', 'img', 'address', 'phone')->where("id", $userId)
            ->with(['stores' => function ($q) {
                $q->select('id', 'name', 'user_id');
            }]);
    }

    private function usersFilter($users, $request)
    {
        if (request()->filled('name')) {
            $users->where('name', 'like', '%' . request()->input('name') . '%');
        }

        if (request()->filled('address')) {
            $users->where('address', 'like', '%' . request()->input('address') . '%');
        }

        if (request()->filled('phone')) {
            $users->where('phone', 'like', '%' . request()->input('phone') . '%');
        }

        if (request()->filled('rule_id')) {
            $users->where('rule_id', request()->input('rule_id'));
        }

        if (request()->filled('email')) {
            $users->where('email', 'like', '%' . request()->input('email') . '%');
        }
        return $users;
    }
    public function saveIamge($ImageName)
    {
        $img = $this->saveImg($ImageName, 'user');
        $this->removeImg(auth()->user()->img, 'user');
        return auth()->user()->update(['img' => $img]);
    }
    public function removeIamge($user)
    {
        $this->removeImg(auth()->user()->img, 'user');
        return auth()->user()->update(['img' => '']);
    }

    public function getUsersByStatus($users, $status)
    {
        if ($status == 2) {
            $users->withTrashed();
        } else if ($status == 0) {
            $users->onlyTrashed();
        }
        return $users;
    }

    public function checkRule($users)
    {
        if (auth()->user()->rule_id != 3) {
            return $users->whereNotIn('rule_id', [3,2]);
        }
        return $users;
    }
}
