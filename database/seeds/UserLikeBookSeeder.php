<?php

use Illuminate\Database\Seeder;

class UserLikeBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $books = \App\Models\Book::all();
        $users = \App\Models\User::all();

        $n_books = min([300, count($books)]);
        $n_users = count($users);

        $like_mapping = [];

        $n_samples = 500;

        for($i=0;$i<$n_samples;$i++){
            $uid = $users[rand(0, $n_users-1)]->id;
            $bid = $books[rand(0, $n_books-1)]->id;
            while(array_key_exists($uid, $users) and array_key_exists($bid, $like_mapping[$uid])){
                $uid = $users[rand(0, $n_users-1)]->id;
                $bid = $books[rand(0, $n_books-1)]->id;
            }
            if(!array_key_exists($uid, $users)) $like_mapping[$uid] = array();
            array_push($like_mapping[$uid], $bid);

            DB::table("user_like_book")->insert([
                'user_id' => $uid,
                'book_id' => $bid,
            ]);
        }
        
    }
}
