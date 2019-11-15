<?php


class class_wp_widget_competition_rating extends WP_Widget {

    public function __construct() {

        $widget_options = [
            'classname' => 'wp_widget_competition_rating',
            'description' => __('Affichage des résultats d\'une compétition'),
            'customize_selective_refresh' => true,
        ];

        parent::__construct( 'widget_competition_rating', __('Classement d\'une compétition', 'competition rating'), $widget_options );
    }

    public function form( $instance ) {

        $instance = wp_parse_args( (array) $instance, array('competition_number'=>'', 'number'=>''));

        echo '<p>Affichage du classement d\'une compétition :</p>';

        echo '<label for="'.$this->get_field_id('competition_number').'">Numéro de la compétition :</label>';
        echo '<input class="widefat" id="'.$this->get_field_id('competition_number').'" '.
            'type="text" name="'.$this->get_field_name('competition_number').'" '.
            'value="'.esc_attr($instance['competition_number']).'">';

        echo '<label for="'.$this->get_field_id('number').'">Nombre de participants affichés (5 maximum) :</label>';
        echo '<input class="widefat" id="'.$this->get_field_id('number').'" '.
            'type="text" name="'.$this->get_field_name('number').'" '.
            'value="'.esc_attr($instance['number']).'">';
    }

    public function widget( $args, $instance ) {
        $title = 'Classement';

        $ins = new inscription();

        echo $args['before_widget'];
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        $rating = $ins->findCompetitionbyNumber( $instance['competition_number'] );

        if( ! empty($rating) ) {
            echo '<h5>'.$rating[0]['name'].' (';
            echo $rating[0]['race_number'];

            if( ! empty($rating[0]['round_number']) ) {
                echo '.'.$rating[0]['round_number'].')';
            }
            else {
                echo ')';
            }

            echo '<h5>'.$rating[0]['season'].' : '.$rating[0]['domain'].'</h5>';

            echo'</h5>';

            echo '<p>';
            echo '1. '.$rating[0]['first_member_name'];
            echo '</p>';

            if( (int) $instance['number'] > 1) {
                echo '<p>';
                echo '2. '.$rating[0]['second_member_name'];
                echo '</p>';
            }

            if( (int) $instance['number'] > 2) {
                echo '<p>';
                echo '3. '.$rating[0]['third_member_name'];
                echo '</p>';
            }

            if( (int) $instance['number'] > 3) {
                echo '<p>';
                echo '4. '.$rating[0]['fourth_member_name'];
                echo '</p>';
            }

            if( (int) $instance['number'] > 4) {
                echo '<p>';
                echo '5. '.$rating[0]['fifth_member_name'];
                echo '</p>';
            }
        }
        echo $args['after_widget'];
    }
}