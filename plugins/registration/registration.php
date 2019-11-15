<?php

/*
 * Plugin name: Inscription Modélisme
 * Description: Pour inscrire vos clubs et adhérents
 * Author: Grollemund Bertrand
 * Version: 1.0.0
 */

// TODO foreach pour toutes les places pour pouvoir augmenter le nombre de places attribuant des points
// TODO menu Import/export
// TODO fonction modifier
// TODO fonction vérif des données rentrées // sécuristation requête
// TODO mise en forme des menus admin


require_once  plugin_dir_path(__FILE__).'/inscription.php';
require_once  plugin_dir_path(__FILE__).'/class_wp_widget_ratings.php';
require_once  plugin_dir_path(__FILE__).'/class_wp_widget_competition_rating.php';

class registration {

    public function __construct() {

        register_activation_hook(__FILE__, array('inscription', 'install'));
        register_deactivation_hook(__FILE__, array('inscription', 'uninstall'));

        add_action('admin_menu', array($this, 'add_menu_clubs'));
        add_action('admin_menu', array($this, 'add_menu_members'));
        add_action('admin_menu', array($this, 'add_menu_competitions'));
        add_action('admin_menu', array($this, 'add_menu_ratings'));
        add_action('admin_menu', array($this, 'add_menu_stats'));
        add_action('admin_menu', array($this, 'add_menu_import'));

        add_action( 'widgets_init',
            function() {
                register_widget( 'class_wp_widget_ratings' );
            });

        add_action( 'widgets_init',
            function() {
                register_widget( 'class_wp_widget_competition_rating' );
            });
    }

    public function add_menu_clubs() {
        $hook = add_menu_page("Les clubs",
            "Clubs", 'manage_options',
            "ClubsModelisme", array($this, 'myMenuClub'),
            'dashicons-groups', 46);

        add_submenu_page("ClubsModelisme", "Ajouter un club",
            "Ajouter", "manage_options", "suberClub",
            array($this, 'myMenuClub'));
    }

    public function add_menu_members() {
        $hook = add_menu_page("Les adhérents",
            "Adhérents", 'manage_options',
            "MembersModelisme", array($this, 'myMenuMember'),
            'dashicons-admin-users', 47);

        add_submenu_page("MembersModelisme", "Ajouter un adhérent",
            "Ajouter", "manage_options", "suberMember",
            array($this, 'myMenuMember'));
    }

    public function add_menu_competitions() {
        $hook = add_menu_page("Les compétitions",
            "Compétitions", 'manage_options',
            "CompetitionsModelisme", array($this, 'myMenuCompetition'),
            'dashicons-clipboard', 48);

        add_submenu_page("CompetitionsModelisme", "Ajouter une compétition",
            "Ajouter", "manage_options", "suberCompetition",
            array($this, 'myMenuCompetition'));
    }

    public function add_menu_ratings() {
        $hook = add_menu_page("Les classements",
            "Classements", 'manage_options',
            "RatingsModelisme", array($this, 'myMenuRating'),
            'dashicons-editor-ol', 49);

        add_submenu_page("RatingsModelisme", "Système de classement",
            "Système de classement", "manage_options", "suberSystemRating",
            array($this, 'myMenuRating'));

        add_submenu_page("RatingsModelisme", "Ajouter un système de classement",
            "Ajouter", "manage_options", "suberAddRating",
            array($this, 'myMenuRating'));
    }

    public function add_menu_stats() {
        $hook = add_menu_page("Les statistiques",
            "Statistiques", 'manage_options',
            "StatsModelisme", array($this, 'myMenuStat'),
            'dashicons-chart-line', 50);
    }

    public function add_menu_import() {
        $hook = add_menu_page("Import / Export",
            "Import / Export", 'manage_options',
            "ImportModelisme", array($this, 'myMenuImport'),
            'dashicons-download', 51);
    }

    public function myMenuClub() {

        if( $_GET['page'] == 'ClubsModelisme' || isset($_POST['name']) ) {

            echo '<h1>Les clubs d\'Occitanie</h1>';

            $ins = new inscription();

            if( isset($_POST['name']) ) {
                $ins->saveClub();
            }

            if( isset($_POST['delete']) ) {
                $ins->deleteById('modelisme_clubs', $_POST['delete']);
            }

            echo '<form action="" method="post">';
            echo '<table class="widefat fixed" cellspacing="0">';
            echo '<tr><th class="manage-column column-columnname" scope="col"><input type="submit" value="Supprimer"></th>'.
                '<th class="manage-column column-columnname" scope="col">Nom</th>'.
                '<th class="manage-column column-columnname" scope="col">Adresse</th>'.
                '<th class="manage-column column-columnname" scope="col">Code postal</th>'.
                '<th class="manage-column column-columnname" scope="col">Ville</th>'.
                '<th class="manage-column column-columnname" scope="col">Email</th>'.
                '<th class="manage-column column-columnname" scope="col">Téléphone</th>'.
                '<th class="manage-column column-columnname" scope="col">Domaine de prédilection</th>'.
                '<th class="manage-column column-columnname" scope="col">Participation aux compétitions</th></tr>';
            foreach( $ins->findAllClubs() as $line ) {
                echo '<tr style="border:1px solid #000">';
                echo '<td><input type="checkbox" id="delete_'.$line['id'].'" name="delete[]" value="'.$line['id'].'">';
                echo '<i class="dashicons dashicons-trash"></i>';
                echo '</td>';
                echo '<td>'.$line['name'].'</td>';
                echo '<td>'.$line['address'].'</td>';
                echo '<td>'.$line['postal_code'].'</td>';
                echo '<td>'.$line['city'].'</td>';
                echo '<td>'.$line['email'].'</td>';
                echo '<td>'.$line['phone_number'].'</td>';
                echo '<td>'.$line['favorite_domain'].'</td>';
                echo '<td>'.$line['is_participant'].'</td>';
                echo '</tr>';
            }
            echo '</table>';
            echo '</form>';
        }
        else {

            echo '<h1>Ajouter un club</h1>';

            echo '<form action="" method="post">';
            echo '<p>';
            echo '<label for="name">Nom :</label>';
            echo '<input class="widefat" id="name" name="name" type="text" value="">';
            echo '</p>';
            echo '<p>';
            echo '<label for="address">Adresse :</label>';
            echo '<input class="widefat" id="address" name="address" type="text" value="">';
            echo '</p>';
            echo '<p>';
            echo '<label for="postal_code">Code Postal :</label>';
            echo '<input class="widefat" id="postal_code" name="postal_code" type="text" value="">';
            echo '</p>';
            echo '<p>';
            echo '<label for="city">Ville :</label>';
            echo '<input class="widefat" id="city" name="city" type="text" value="">';
            echo '</p>';
            echo '<p>';
            echo '<label for="email">Email :</label>';
            echo '<input class="widefat" id="email" name="email" type="text" value="">';
            echo '</p>';
            echo '<p>';
            echo '<label for="phone_number">Téléphone :</label>';
            echo '<input class="widefat" id="phone_number" name="phone_number" type="text" value="">';
            echo '</p>';
            echo '<p>';
            echo '<label for="favorite_domain_select">Domaine de prédilection : </label>';
            echo '<select name="favorite_domain" id="favorite_domain_select">';
            echo '<option value="">Choisissez un domaine</option>';
            echo '<option value="Automobiles radio commandées">Automobiles radio commandées</option>';
            echo '<option value="Modélisme aérien">Modélisme aérien</option>';
            echo '<option value="Modélisme naval">Modélisme naval</option>';
            echo '</select>';
            echo '</p>';
            echo '<p>Participation aux compétitions : </p>';
            echo '<div>';
            echo '<input type="radio" id="participant" name="is_participant" value="oui" checked>';
            echo '<label for="participant"> Participe </label>';
            echo '</div>';
            echo '<div>';
            echo '<input type="radio" id="not_participant" name="is_participant" value="non">';
            echo '<label for="not_participant"> Ne participe pas </label>';
            echo '</div>';
            echo '<p><input type="submit" value="Enregister"></p>';
            echo '</form>';
        }
    }

    public function myMenuMember() {

        $ins = new inscription();

        if( $_GET['page'] == 'MembersModelisme' || isset($_POST['last_name']) ) {

            echo '<h1>Les adhérents d\'Occitanie</h1>';

            if( isset($_POST['last_name']) ) {
                $ins->saveMember();
            }

            if( isset($_POST['delete']) ) {
                $ins->deleteById('modelisme_members', $_POST['delete']);
            }

            echo '<form action="" method="post">';
            echo '<table class="widefat fixed" cellspacing="0">';
            echo '<tr><th class="manage-column column-columnname" scope="col"><input type="submit" value="Supprimer"></th>'.
                '<th class="manage-column column-columnname" scope="col">Nom</th>'.
                '<th class="manage-column column-columnname" scope="col">Prénom</th>'.
                '<th class="manage-column column-columnname" scope="col">Adresse</th>'.
                '<th class="manage-column column-columnname" scope="col">Code postal</th>'.
                '<th class="manage-column column-columnname" scope="col">Ville</th>'.
                '<th class="manage-column column-columnname" scope="col">Email</th>'.
                '<th class="manage-column column-columnname" scope="col">Téléphone</th>'.
                '<th class="manage-column column-columnname" scope="col">Numéro d\'adhérent</th>'.
                '<th class="manage-column column-columnname" scope="col">Club</th></tr>';
            foreach( $ins->findAllMembers() as $line ) {
                echo '<tr style="border:1px solid #000">';
                echo '<td><input type="checkbox" id="delete_'.$line['id'].'" name="delete[]" value="'.$line['id'].'">';
                echo '<i class="dashicons dashicons-trash"></i>';
                echo '</td>';
                echo '<td>'.$line['last_name'].'</td>';
                echo '<td>'.$line['first_name'].'</td>';
                echo '<td>'.$line['address'].'</td>';
                echo '<td>'.$line['postal_code'].'</td>';
                echo '<td>'.$line['city'].'</td>';
                echo '<td>'.$line['email'].'</td>';
                echo '<td>'.$line['phone_number'].'</td>';
                echo '<td>'.$line['member_number'].'</td>';
                echo '<td>'.$ins->findNameClubById( $line['club_id'] ).'</td>';
                echo '</tr>';
            }
            echo '</table>';
            echo '</form>';
        }
        else {

            echo '<h1>Ajouter un adhérent</h1>';

            echo '<form action="" method="post">';
            echo '<p>';
            echo '<label for="last_name">Nom :</label>';
            echo '<input class="widefat" id="last_name" name="last_name" type="text" value="">';
            echo '</p>';
            echo '<p>';
            echo '<label for="first_name">Prénom :</label>';
            echo '<input class="widefat" id="first_name" name="first_name" type="text" value="">';
            echo '</p>';
            echo '<p>';
            echo '<label for="address">Adresse :</label>';
            echo '<input class="widefat" id="address" name="address" type="text" value="">';
            echo '</p>';
            echo '<p>';
            echo '<label for="postal_code">Code Postal :</label>';
            echo '<input class="widefat" id="postal_code" name="postal_code" type="text" value="">';
            echo '</p>';
            echo '<p>';
            echo '<label for="city">Ville :</label>';
            echo '<input class="widefat" id="city" name="city" type="text" value="">';
            echo '</p>';
            echo '<p>';
            echo '<label for="email">Email :</label>';
            echo '<input class="widefat" id="email" name="email" type="text" value="">';
            echo '</p>';
            echo '<p>';
            echo '<label for="phone_number">Téléphone :</label>';
            echo '<input class="widefat" id="phone_number" name="phone_number" type="text" value="">';
            echo '</p>';
            echo '<p>';
            echo '<label for="member_number">Numéro d\'adhérent :</label>';
            echo '<input class="widefat" id="member_number" name="member_number" type="text" value="">';
            echo '</p>';
            echo '<p>';
            echo '<label for="club_select">Club : </label>';
            echo '<select name="club_id" id="club_select">';
            echo '<option value="">Choisissez le club</option>';
            foreach( $ins->findAllClubsName() as $line ) {
                echo '<option value="'. $line['id'] .'">'. $line['name'] .'</option>';
            }
            echo '</select>';
            echo '</p>';
            echo '<p><input type="submit" value="Enregister"></p>';
            echo '</form>';
        }
    }

    public function myMenuCompetition() {

        if( $_GET['page'] == 'CompetitionsModelisme' || isset($_POST['name']) ) {

            echo '<h1>Les compétitions d\'Occitanie</h1>';

            $ins = new inscription();

            if( isset($_POST['name']) ) {
                $ins->saveCompetition();
            }

            if( isset($_POST['delete']) ) {
                $ins->deleteById('modelisme_competitions', $_POST['delete']);
            }

            echo '<form action="" method="post">';
            echo '<table class="widefat fixed" cellspacing="0">';
            echo '<tr><th class="manage-column column-columnname" scope="col"><input type="submit" value="Supprimer"></th>'.
                '<th class="manage-column column-columnname" scope="col">Numéro de la compétion</th>'.
                '<th class="manage-column column-columnname" scope="col">Saison</th>'.
                '<th class="manage-column column-columnname" scope="col">Nom</th>'.
                '<th class="manage-column column-columnname" scope="col">Domaine</th>'.
                '<th class="manage-column column-columnname" scope="col">n° course</th>'.
                '<th class="manage-column column-columnname" scope="col">n° manche</th>'.
                '<th class="manage-column column-columnname" scope="col">1er</th>'.
                '<th class="manage-column column-columnname" scope="col">2nd</th>'.
                '<th class="manage-column column-columnname" scope="col">3ème</th>'.
                '<th class="manage-column column-columnname" scope="col">4ème</th>'.
                '<th class="manage-column column-columnname" scope="col">5ème</th></tr>';
            foreach( $ins->findAllCompetitions() as $line ) {
                echo '<tr style="border:1px solid #000">';
                echo '<td><input type="checkbox" id="delete_'.$line['id'].'" name="delete[]" value="'.$line['id'].'">';
                echo '<i class="dashicons dashicons-trash"></i>';
                echo '</td>';
                echo '<td>'.$line['competition_number'].'</td>';
                echo '<td>'.$line['season'].'</td>';
                echo '<td>'.$line['name'].'</td>';
                echo '<td>'.$line['domain'].'</td>';
                echo '<td>'.$line['race_number'].'</td>';
                echo '<td>'.$line['round_number'].'</td>';

                echo '<td>'.$line['first_member_name'].'</td>';
                echo '<td>'.$line['second_member_name'].'</td>';
                echo '<td>'.$line['third_member_name'].'</td>';
                echo '<td>'.$line['fourth_member_name'].'</td>';
                echo '<td>'.$line['fifth_member_name'].'</td>';
                echo '</tr>';
            }
            echo '</table>';
            echo '</form>';
        }
        else {

            echo '<h1>Ajouter une compétition</h1>';

            echo '<form action="" method="post">';
            echo '<p>';
            echo '<label for="competition_number">Numéro de la compétion :</label>';
            echo '<input class="widefat" id="competition_number" name="competition_number" type="text" value="">';
            echo '</p>';
            echo '<p>';
            echo '<label for="season">Saison :</label>';
            echo '<input class="widefat" id="season" name="season" type="text" value="">';
            echo '</p>';
            echo '<p>';
            echo '<label for="name">Nom :</label>';
            echo '<input class="widefat" id="name" name="name" type="text" value="">';
            echo '</p>';
            echo '<p>';
            echo '<label for="domain_select">Domaine : </label>';
            echo '<select name="domain" id="domain_select">';
            echo '<option value="">Choisissez un domaine</option>';
            echo '<option value="Modèles réduits automobiles">Modèles réduits automobiles</option>';
            echo '<option value="Drones à 3 rotors">Drones à 3 rotors</option>';
            echo '<option value="Drones à 4 rotors">Drones à 4 rotors</option>';
            echo '</select>';
            echo '</p>';
            echo '<p>';
            echo '<label for="race_number">n° course :</label>';
            echo '<input class="widefat" id="race_number" name="race_number" type="text" value="">';
            echo '</p>';
            echo '<p>';
            echo '<label for="round_number">n° manche :</label>';
            echo '<input class="widefat" id="round_number" name="round_number" type="text" value="">';
            echo '</p>';

            echo '<p>';
            echo '<label for="first_member_number">n° adhérent du 1er :</label>';
            echo '<input class="widefat" id="first_member_number" name="first_member_number" type="text" value="">';
            echo '</p>';
            echo '<p>';
            echo '<label for="second_member_number">n° adhérent du 2nd :</label>';
            echo '<input class="widefat" id="second_member_number" name="second_member_number" type="text" value="">';
            echo '</p>';
            echo '<p>';
            echo '<label for="third_member_number">n° adhérent du 3ème :</label>';
            echo '<input class="widefat" id="third_member_number" name="third_member_number" type="text" value="">';
            echo '</p>';
            echo '<p>';
            echo '<label for="fourth_member_number">n° adhérent du 4ème :</label>';
            echo '<input class="widefat" id="fourth_member_number" name="fourth_member_number" type="text" value="">';
            echo '</p>';
            echo '<p>';
            echo '<label for="fifth_member_number">n° adhérent du 5ème :</label>';
            echo '<input class="widefat" id="fifth_member_number" name="fifth_member_number" type="text" value="">';
            echo '</p>';
            echo '<p><input type="submit" value="Enregister"></p>';
            echo '</form>';
        }
    }

    public function myMenuRating() {

        $ins = new inscription();

        if( $_GET['page'] == 'RatingsModelisme' ) {
            echo '<h1>Suivi des classements</h1>';

            echo '<div style="display: flex; justify-content: space-around">';

            foreach( $ins->findAllDomain() as $domain ) {
                echo '<div>';
                echo '<h2>'.$domain['domain'].'</h2>';

                foreach( $ins->findAllSeason() as $season ) {

                    $rating = $ins->calculateRatings( $season['season'], $domain['domain'] );

                    if( ! empty($rating) ) {
                        echo '<h4>'.$season['season'].'</h4>';

                        $i = 1;

                        foreach( $rating as $key => $value ) {
                            echo '<p>';
                            echo $i.'. '.$key.' ('.$value.' points)';
                            echo '</p>';
                            $i++;
                        }
                    }
                }

                echo '</div>';
            }

            echo '</div>';

        }
        elseif( $_GET['page'] == 'suberSystemRating' || isset($_POST['domain']) ) {

            echo '<h1>Les systèmes de classements</h1>';

            if( isset($_POST['domain']) ) {
                $ins->saveRating();
            }

            if( isset($_POST['delete']) ) {
                $ins->deleteById('modelisme_ratings', $_POST['delete']);
            }

            echo '<form action="" method="post">';
            echo '<table class="widefat fixed" cellspacing="0">';
            echo '<tr><th class="manage-column column-columnname" scope="col"><input type="submit" value="Supprimer"></th>'.
                '<th class="manage-column column-columnname" scope="col">Domaine</th>'.
                '<th class="manage-column column-columnname" scope="col">Nombre de points du 1er</th>'.
                '<th class="manage-column column-columnname" scope="col">Nombre de points du 2nd</th>'.
                '<th class="manage-column column-columnname" scope="col">Nombre de points du 3ème</th>'.
                '<th class="manage-column column-columnname" scope="col">Nombre de points du 4ème</th>'.
                '<th class="manage-column column-columnname" scope="col">Nombre de points du 5ème</th></tr>';
            foreach( $ins->findAllRatings() as $line ) {
                echo '<tr style="border:1px solid #000">';
                echo '<td><input type="checkbox" id="delete_'.$line['id'].'" name="delete[]" value="'.$line['id'].'">';
                echo '<i class="dashicons dashicons-trash"></i>';
                echo '</td>';
                echo '<td>'.$line['domain'].'</td>';
                echo '<td>'.$line['first'].'</td>';
                echo '<td>'.$line['second'].'</td>';
                echo '<td>'.$line['third'].'</td>';
                echo '<td>'.$line['fourth'].'</td>';
                echo '<td>'.$line['fifth'].'</td>';
                echo '</tr>';
            }
            echo '</table>';
            echo '</form>';

        }
        elseif( $_GET['page'] == 'suberAddRating' )  {

            echo '<h1>Ajouter un système de classement</h1>';

            echo '<form action="" method="post">';
            echo '<label for="domain_select">Domaine : </label>';
            echo '<select name="domain" id="domain_select">';
            echo '<option value="">Choisissez un domaine</option>';
            echo '<option value="Modèles réduits automobiles">Modèles réduits automobiles</option>';
            echo '<option value="Drones à 3 rotors">Drones à 3 rotors</option>';
            echo '<option value="Drones à 4 rotors">Drones à 4 rotors</option>';
            echo '</select>';
            echo '</p>';

            echo '<p>';
            echo '<label for="first">Nombre de points du 1er :</label>';
            echo '<input class="widefat" id="first" name="first" type="text" value="">';
            echo '</p>';
            echo '<p>';
            echo '<label for="second">Nombre de points du 2nd :</label>';
            echo '<input class="widefat" id="second" name="second" type="text" value="">';
            echo '</p>';
            echo '<p>';
            echo '<label for="third">Nombre de points du 3ème :</label>';
            echo '<input class="widefat" id="third" name="third" type="text" value="">';
            echo '</p>';
            echo '<p>';
            echo '<label for="fourth">Nombre de points du 4ème :</label>';
            echo '<input class="widefat" id="fourth" name="fourth" type="text" value="">';
            echo '</p>';
            echo '<p>';
            echo '<label for="fifth_member_number">Nombre de points du 5ème :</label>';
            echo '<input class="widefat" id="fifth" name="fifth" type="text" value="">';
            echo '</p>';
            echo '<p><input type="submit" value="Enregister"></p>';
            echo '</form>';
        }
    }

    public function myMenuStat() {

        $ins = new inscription();

        echo '<h2>Effectif</h2>';

        echo '<p>Nombre de clubs : '.$ins->countClubs().'</p>';
        echo '<p>Nombre d\'adhérents : '.$ins->countMembers().'</p>';

        echo '<h4>Par club</h4>';

        echo '<table class="widefat fixed" cellspacing="0">';
        echo '<tr><th class="manage-column column-columnname" scope="col">Club</th>'.
            '<th class="manage-column column-columnname" scope="col">Nombre d\'adhérents</th></tr>';
        foreach( $ins->countMembersByClubs() as $line ) {
            echo '<tr style="border:1px solid #000">';
            echo '<td>'.$line['club_name'].'</td>';
            echo '<td>'.$line['total'].'</td>';
            echo '</tr>';
        }
        echo '</table>';

        echo '<h2>Répartition Régionale</h2>';
        echo '<h4>Par département</h4>';

        echo '<table class="widefat fixed" cellspacing="0">';
        echo '<tr><th class="manage-column column-columnname" scope="col"></th>'.
            '<th class="manage-column column-columnname" scope="col">Nombre de clubs</th>'.
            '<th class="manage-column column-columnname" scope="col">Nombre d\'adhérents</th></tr>';
        foreach( $ins->findDepStats() as $line ) {
            echo '<tr style="border:1px solid #000">';
            echo '<td>'.$line['dep'].'</td>';
            echo '<td>'.$line['clubs_total'].'</td>';
            echo '<td>'.$line['members_total'].'</td>';
            echo '</tr>';
        }
        echo '</table>';

        echo '<h2>Domaine de prédilection des clubs</h2>';
        echo '<table class="widefat fixed" cellspacing="0">';
        echo '<tr><th class="manage-column column-columnname" scope="col">Domaine de prédilection</th>'.
            '<th class="manage-column column-columnname" scope="col">Nombre de clubs</tr>';
        foreach( $ins->findDomainByClub() as $line ) {
            echo '<tr style="border:1px solid #000">';
            echo '<td>'.$line['favorite_domain'].'</td>';
            echo '<td>'.$line['clubs_number'].'</td>';
            echo '</tr>';
        }
        echo '</table>';

    }

    public function myMenuImport() {

        $ins = new inscription();

        if (isset($_POST['fill']) && $_POST['fill'] == 'oui') {
            $ins->insertDefaultValues();
        }

        echo '<form action="" method="post">';
        echo '<p>Importer les données par défaut : </p>';
        echo '<div>';
        echo '<input type="radio" id="fill" name="fill" value="oui" checked>';
        echo '<label for="fill"> Oui </label>';
        echo '</div>';
        echo '<div>';
        echo '<input type="radio" id="not_fill" name="fill" value="non" checked>';
        echo '<label for="not_fill"> Non </label>';
        echo '</div>';
        echo '<p><input type="submit" value="OK"></p>';
        echo '</form>';
    }
}

new registration();