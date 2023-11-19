<?php

    namespace App\Http\Traits;

    trait UtilTrait {
        public function access_model( $user, $model ) {
            $home = $user->home;
            $access = false;

            if( $model->user_id ) {
                if( $model->user_id == $user->id ) {
                    $access = true;
                }
            } else {
                if( $model->home_id == $home->id ) {
                    $access = true;
                }
            }
            return $access;
        }
    }

?>
