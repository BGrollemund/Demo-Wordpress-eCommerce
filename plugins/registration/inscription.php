<?php

class inscription {

    public static function install() {
        global $wpdb;

        $wpdb->query("CREATE TABLE IF NOT EXISTS ".
            "{$wpdb->prefix}modelisme_clubs (id INT AUTO_INCREMENT PRIMARY KEY, ".
            "name VARCHAR(150) NOT NULL, address VARCHAR(255) NOT NULL, ".
            "postal_code VARCHAR(5) NOT NULL, city VARCHAR(255) NOT NULL, ".
            "email VARCHAR(255) NOT NULL, phone_number VARCHAR(10) NOT NULL, ".
            "favorite_domain VARCHAR(255) NOT NULL, is_participant VARCHAR(3) NOT NULL);");

        $wpdb->query("CREATE TABLE IF NOT EXISTS ".
            "{$wpdb->prefix}modelisme_members (id INT AUTO_INCREMENT PRIMARY KEY, ".
            "last_name VARCHAR(150) NOT NULL, first_name VARCHAR(200) NOT NULL, ".
            "address VARCHAR(255), postal_code VARCHAR(5), ".
            "city VARCHAR(255), email VARCHAR(255), ".
            "phone_number VARCHAR(10), member_number VARCHAR(10) NOT NULL, club_id INT NOT NULL);");

        $wpdb->query("CREATE TABLE IF NOT EXISTS ".
            "{$wpdb->prefix}modelisme_competitions (id INT AUTO_INCREMENT PRIMARY KEY, ".
            "competition_number VARCHAR(255) NOT NULL, season VARCHAR(150) NOT NULL, name VARCHAR(150) NOT NULL, ".
            "domain VARCHAR(255) NOT NULL, race_number VARCHAR(3) NOT NULL, round_number VARCHAR(2), ".
            "first_member_id INT, first_member_name VARCHAR(255), ".
            "second_member_id INT, second_member_name VARCHAR(255), ".
            "third_member_id INT, third_member_name VARCHAR(255), ".
            "fourth_member_id INT, fourth_member_name VARCHAR(255), ".
            "fifth_member_id INT, fifth_member_name VARCHAR(255));");

        $wpdb->query("CREATE TABLE IF NOT EXISTS ".
            "{$wpdb->prefix}modelisme_ratings (id INT AUTO_INCREMENT PRIMARY KEY, ".
            "domain VARCHAR(255) NOT NULL, first INT, second INT, third INT, fourth INT, fifth INT);");

        $default_table_ratings = [
            ['Modèles réduits automobiles', 8, 6, 4, 2, 1],
            ['Drones à 3 rotors', 50, 25, 10, 5, 0],
            ['Drones à 4 rotors', 50, 25, 10, 5, 0],
        ];

        foreach($default_table_ratings as $table) {
            $wpdb->insert("{$wpdb->prefix}modelisme_ratings", array(
                'domain' => $table[0],
                'first' => $table[1],
                'second' => $table[2],
                'third' => $table[3],
                'fourth' => $table[4],
                'fifth' => $table[5]
            ));
        }
    }

    public static function uninstall() {
        global $wpdb;

        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}modelisme_clubs, {$wpdb->prefix}modelisme_members, {$wpdb->prefix}modelisme_ratings, {$wpdb->prefix}modelisme_competitions;" );
    }

    public function saveClub() {
        global $wpdb;

        if( isset( $_POST['name'] ) && !empty($_POST['name']) ) {
            $name = $_POST['name'];
            $address = $_POST['address'];
            $postal_code = $_POST['postal_code'];
            $city = $_POST['city'];
            $email = $_POST['email'];
            $phone_number = $_POST['phone_number'];
            $favorite_domain = $_POST['favorite_domain'];
            $is_participant = $_POST['is_participant'];


            $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}modelisme_clubs WHERE email='".$email."';");

            if( is_null( $row ) ) {
                $wpdb->insert("{$wpdb->prefix}modelisme_clubs", array(
                    'name' => $name,
                    'address' => $address,
                    'postal_code' => $postal_code,
                    'city' => $city,
                    'email' => $email,
                    'phone_number' => $phone_number,
                    'favorite_domain' => $favorite_domain,
                    'is_participant' => $is_participant));
            }
        }
    }

    public function saveMember() {
        global $wpdb;

        if( isset( $_POST['last_name'] ) && !empty($_POST['last_name']) ) {
            $last_name = $_POST['last_name'];
            $first_name = $_POST['first_name'];
            $address = $_POST['address'];
            $postal_code = $_POST['postal_code'];
            $city = $_POST['city'];
            $email = $_POST['email'];
            $phone_number = $_POST['phone_number'];
            $member_number = $_POST['member_number'];
            $club_id = $_POST['club_id'];


            $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}modelisme_clubs WHERE member_number='".$member_number."';");

            if( is_null( $row ) ) {
                $wpdb->insert("{$wpdb->prefix}modelisme_members", array(
                    'last_name' => $last_name,
                    'first_name' => $first_name,
                    'address' => $address,
                    'postal_code' => $postal_code,
                    'city' => $city,
                    'email' => $email,
                    'phone_number' => $phone_number,
                    'member_number' => $member_number,
                    'club_id' => $club_id));
            }
        }
    }

    public function saveCompetition() {
        global $wpdb;

        function findIdbyNumber($number) {
            global $wpdb;
            $res = $wpdb->get_results("SELECT id FROM {$wpdb->prefix}modelisme_members WHERE member_number='".$number."';", ARRAY_A);
            return $res[0]['id'];
        }

        function findMemberByNumber( $number ) {
            global $wpdb;
            $res = $wpdb->get_results("SELECT last_name, first_name FROM {$wpdb->prefix}modelisme_members WHERE member_number='".$number."';", ARRAY_A);
            return $res[0]['last_name'].' '.$res[0]['first_name'];
        }

        if( isset( $_POST['competition_number'] ) && !empty($_POST['competition_number']) ) {

            $competition_number = $_POST['competition_number'];
            $season = $_POST['season'];
            $name = $_POST['name'];
            $domain = $_POST['domain'];
            $race_number = $_POST['race_number'];
            $round_number = $_POST['round_number'];

            foreach( ['first', 'second', 'third', 'fourth', 'fifth'] as $rank ) {
                if( isset( $_POST[$rank.'_member_number'] ) ) {
                    ${$rank.'_member_name'} = findMemberByNumber($_POST[$rank.'_member_number']);
                    ${$rank.'_member_id'} = (int) findIdbyNumber($_POST[$rank.'_member_number']);
                }
            }


            $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}modelisme_competitions WHERE competition_number='".$competition_number."';");

            if( is_null( $row ) ) {
                $wpdb->insert("{$wpdb->prefix}modelisme_competitions", array(
                    'competition_number' => $competition_number,
                    'season' => $season,
                    'name' => $name,
                    'domain' => $domain,
                    'race_number' => $race_number,
                    'round_number' => $round_number,
                    'first_member_name' => $first_member_name,
                    'first_member_id' => $first_member_id,
                    'second_member_name' => $second_member_name,
                    'second_member_id' => $second_member_id,
                    'third_member_name' => $third_member_name,
                    'third_member_id' => $third_member_id,
                    'fourth_member_name' => $fourth_member_name,
                    'fourth_member_id' => $fourth_member_id,
                    'fifth_member_name' => $fifth_member_name,
                    'fifth_member_id' => $fifth_member_id));
            }
        }
    }

    public function saveRating()
    {
        global $wpdb;

        if (isset($_POST['domain']) && !empty($_POST['domain'])) {
            $domain = $_POST['domain'];
            $first = $_POST['first'];
            $second = $_POST['second'];
            $third = $_POST['third'];
            $fourth = $_POST['fourth'];
            $fifth = $_POST['fifth'];

            $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}modelisme_ratings WHERE domain='".$domain."';");

            if( is_null( $row ) ) {
                $wpdb->insert("{$wpdb->prefix}modelisme_ratings", array(
                    'domain' => $domain,
                    'first' => $first,
                    'second' => $second,
                    'third' => $third,
                    'fourth' => $fourth,
                    'fifth' => $fifth));
            }
        }
    }

    public function deleteById($table, $id) {
        if( !is_array($id) ) {
            $id = array($id);
        }

        global $wpdb;
        $wpdb->query("DELETE FROM {$wpdb->prefix}".$table." WHERE id in (".implode(',', $id).");");

    }

    public function findAllClubs() {
        global $wpdb;
        $res = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}modelisme_clubs", ARRAY_A);
        return $res;
    }

    public function findAllClubsName() {
        global $wpdb;
        $res = $wpdb->get_results("SELECT id, name FROM {$wpdb->prefix}modelisme_clubs", ARRAY_A);
        return $res;
    }

    public function findNameClubById( $id ) {
        global $wpdb;
        $res = $wpdb->get_results("SELECT name FROM {$wpdb->prefix}modelisme_clubs WHERE id='".$id."';", ARRAY_A);
        return $res[0]['name'];
    }

    public function findAllMembers() {
        global $wpdb;
        $res = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}modelisme_members", ARRAY_A);
        return $res;
    }

    public function findAllCompetitions() {
        global $wpdb;
        $res = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}modelisme_competitions", ARRAY_A);
        return $res;
    }

    public function findCompetitionbyNumber( $number ) {
        global $wpdb;
        $res = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}modelisme_competitions WHERE competition_number=".$number.";", ARRAY_A);
        return $res;
    }

    public function findAllDomain() {
        global $wpdb;
        $res = $wpdb->get_results("SELECT domain FROM {$wpdb->prefix}modelisme_competitions GROUP BY domain", ARRAY_A);
        return $res;
    }

    public function findDomainByClub() {
        global $wpdb;
        $res = $wpdb->get_results("SELECT favorite_domain, COUNT(id) as clubs_number FROM {$wpdb->prefix}modelisme_clubs".
            " GROUP BY favorite_domain ORDER BY clubs_number DESC", ARRAY_A);
        return $res;
    }

    public function findAllSeason() {
        global $wpdb;
        $res = $wpdb->get_results("SELECT season FROM {$wpdb->prefix}modelisme_competitions GROUP BY season", ARRAY_A);
        return $res;
    }

    public function findAllRatings() {
        global $wpdb;
        $res = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}modelisme_ratings", ARRAY_A);
        return $res;
    }

    public function findDepStats() {

        $dep_table = [
            '09' => 'Ariège', '11' => 'Aude', '12' => 'Aveyron', '30' => 'Gard', '31' => 'Haute-Garonne',
            '32' => 'Gers', '34' => 'Hérault', '46' => 'Lot', '48' => 'Lozère', '65' => 'Hautes-Pyrénées',
            '66' => 'Pyrénées-Orientales', '81' => 'Tarn', '82' => 'Tarn-et-Garonne',
        ];

        global $wpdb;

        $infos_clubs = $wpdb->get_results("SELECT substr(postal_code,1,2) as dep, COUNT(substr(postal_code,1,2)) AS clubs_total ".
            "FROM {$wpdb->prefix}modelisme_clubs GROUP BY dep ORDER BY dep;", ARRAY_A);

        $infos_members = $wpdb->get_results("SELECT substr({$wpdb->prefix}modelisme_clubs.postal_code,1,2) as dep, ".
            "COUNT({$wpdb->prefix}modelisme_members.id) AS members_total FROM {$wpdb->prefix}modelisme_clubs ".
            "LEFT JOIN {$wpdb->prefix}modelisme_members ON {$wpdb->prefix}modelisme_clubs.id = {$wpdb->prefix}modelisme_members.club_id ".
            "GROUP BY dep ORDER BY dep;", ARRAY_A);

        foreach( $infos_clubs as $key_cl => $value_cl ) {

            foreach( $infos_members as $key_mb => $value_mb ) {
                if( $infos_clubs[$key_cl]['dep'] == $infos_members[$key_cl]['dep'] ) {
                    $infos_clubs[$key_cl]['members_total'] = $infos_members[$key_cl]['members_total'];
                    continue;
                }
            }

            $infos_clubs[$key_cl]['dep'] = $dep_table[$value_cl['dep']];
        }

        return $infos_clubs;
    }

    public function insertDefaultValues() {

        global $wpdb;

        $default_table_clubs = [
            ['Les As du Modèles', '12, avenue de la mer', '34200', 'Sète', 'as-modele@gmail.com', '0467521512', 'Modélisme naval', 'oui'],
            ['Modelise', '452, rue de la République', '11000', 'Carcassonne', 'modelise@hotmail.fr', '0466252986', 'Modélisme aérien', 'oui'],
            ['Montpellier Modélisme', '4, place Jean Jaurès', '34000', 'Montpellier', 'mont-mod@yahoo.fr', '0467201526', 'Automobiles radio commandées', 'oui'],
            ['Toulouse Avions', '125, avenue de l\'aérostapiale', '31000', 'Toulouse', 'toulouseavion@gmail.com', '0499252729', 'Modélisme aérien', 'non'],
            ['Auto et Modèle', '4, rue Rimbaud', '30000', 'Nîmes', 'automodel@hotmail.com', '0467784512', 'Modélisme aérien', 'non'],
            ['Modélisme de l\'Armagnac', '86, boulevard des Mousquetaires', '32000', 'Auch', 'modelisme-armagnac@gmail.com', '0456865957', 'Automobiles radio commandées', 'oui'],
            ['Lozère Passion Modélisme', '8, rue de la Butte', '48000', 'Mende', 'lozeremodele@caramail.com', '0465685912', 'Modélisme aérien', 'non'],
            ['Petits Avions et chapeaux pointus', '14, place de l\'école', '46000', 'Cahors', 'ecole-cahors@gmail.com', '0465482615', 'Modélisme naval', 'non'],
            ['Modélisme Foix', '159, avenue du général De Gaulle', '09000', 'Foix', 'foix-modele@aol.com', '0759263549', 'Automobiles radio commandées', 'oui'],
            ['Millau Avion Miniature', '45, rue du pont', '12100', 'Millau', 'millau-avion@gmail.com', '0462355957', 'Modélisme aérien', 'oui'],
        ];

            foreach($default_table_clubs as $table) {
                $wpdb->insert("{$wpdb->prefix}modelisme_clubs", array(
                    'name' => $table[0],
                    'address' => $table[1],
                    'postal_code' => $table[2],
                    'city' => $table[3],
                    'email' => $table[4],
                    'phone_number' => $table[5],
                    'favorite_domain' => $table[6],
                    'is_participant' => $table[7]
                ));
        }

        $default_table_members = [
            ['Blangero', 'Olivier', '15, avenue de la Mer', '66000', 'Perpignan', 'olivier@lidem.eu', '0612457896', '0001', '3'],
            ['Faure', 'Sébatien', '16, avenue de la Mer', '11000', 'Carcassonne', 'sebastien@lidem.eu', '0625682458', '0002', '8'],
            ['Perez', 'Jean-Luc', '17, avenue de la Mer', '48000', 'Mende', 'jluc@gmail.com', '0777595626', '0003', '7'],
            ['Kenobi', 'Obiwan', '18, avenue de la Mer', '66000', 'Perpignan', 'obbiii34@yahoo.fr', '0635762914', '0004', '4'],
            ['Skywalker', 'Luke', '15, avenue de la Mer', '66000', 'Perpignan', '15luke@gmail.com', '0616873924', '0005', '2'],
            ['Kasparov', 'Garry', '15, avenue de la Mer', '34000', 'Montpellier', 'g-kasparov@aol.fr', '0685624485', '0006', '1'],
            ['Di Nicolo', 'Hervé', '13, avenue de la Mer', '48000', 'Mende', 'h-di-nicolo@yahoo.fr', '0627695263', '0007', '1'],
            ['Nougaro', 'Charles', '1365, avenue de la Mer', '32000', 'Auch', 'charly@gmail.com', '0758962142', '0008', '3'],
            ['Brel', 'Jacques', '25, avenue de la Mer', '09000', 'Foix', 'brel.jacques@lidem.eu', '0712235568', '0009', '5'],
            ['Krit', 'John', '52, avenue de la Mer', '66000', 'Perpignan', 'johnkrit19@lidem.eu', '0628856954', '0010', '7'],
            ['Hernandez', 'Lucas', '12, avenue de la Mer', '30000', 'Nîmes', 'lhernandezz@aol.fr', '0644526355', '0011', '6'],
            ['Heruit', 'Bastien', '155, avenue de la Mer', '34000', 'Montpellier', 'bast_heruit@gmail.com', '0622586954', '0012', '9'],
            ['Dretio', 'Jacqueline', '58, avenue de la Mer', '11000', 'Carcassonne', 'jacqueline456@gmail.com', '0799562352', '0013', '10'],
            ['Serti', 'Olivia', '25, avenue de la Mer', '32000', 'Auch', 'serti-o@gmail.com', '0766525527', '0014', '10'],
            ['Jerd', 'Nathan', '1, avenue de la Mer', '48000', 'Mende', 'jerd.nath@yahoo.fr', '0758623321', '0015', '4'],
            ['Grimault', 'Lucas', '15, avenue de la Mer', '09000', 'Foix', 'lucasg@gmail.com', '0628524282', '0016', '5'],
            ['Grimault', 'Fabien', '15, avenue de la Mer', '09000', 'Foix', 'lucasg@gmail.com', '0628856624', '0017', '1'],
            ['Dalton', 'Joe', '19, avenue de la Mer', '66000', 'Perpignan', 'jdalton@lidem.eu', '0645367526', '0018', '4'],
        ];

        foreach($default_table_members as $table) {
            $wpdb->insert("{$wpdb->prefix}modelisme_members", array(
                'last_name' => $table[0],
                'first_name' => $table[1],
                'address' => $table[2],
                'postal_code' => $table[3],
                'city' => $table[4],
                'email' => $table[5],
                'phone_number' => $table[6],
                'member_number' => $table[7],
                'club_id' => $table[8]
            ));
        }

        $default_table_competitions = [
            ['0001', '2018-2019', 'Course de Blagnac', 'Modèles réduits automobiles', '1', '', 'Blangero Olivier', '1', 'Perez Jean-Luc', '3', 'Hernandez Lucas', '11', 'Nougaro Charles', '8', 'Skywalker Luke', '5'],
            ['0002', '2018-2019', 'Course de Mende', 'Modèles réduits automobiles', '2', '', 'Blangero Olivier', '1', 'Krit John', '10', 'Kasparov Garry', '6', 'Perez Jean-Luc', '3', 'Kenobi Obiwan', '4'],
            ['0003', '2018-2019', 'Course de Toulouse', 'Modèles réduits automobiles', '3', '', 'Blangero Olivier', '1', 'Perez Jean-Luc', '3', 'Krit John', '10', 'Di Nicolo Hervé', '7', 'Kasparov Garry', '6'],
            ['0004', '2018-2019', 'Course de Béziers', 'Modèles réduits automobiles', '4', '', 'Kasparov Garry', '6', 'Blangero Olivier', '1', 'Serti Olivia', '14', 'Nougaro Charles', '8', 'Di Nicolo Hervé', '7'],
            ['0005', '2018-2019', 'Course de Mirepoix', 'Modèles réduits automobiles', '5', '', 'Brel Jacques', '9', 'Skywalker Luke', '5', 'Grimault Fabien', '17', 'Di Nicolo Hervé', '7', 'Kenobi Obiwan', '4'],
            ['0006', '2018-2019', 'Course de Lodève', 'Modèles réduits automobiles', '6', '', 'Nougaro Charles', '8', 'Perez Jean-Luc', '3', 'Blangero Olivier', '01', 'Brel Jacques', '9', 'Kenobi Obiwan', '4'],
            ['0007', '2018-2019', 'Course de St-Estève', 'Modèles réduits automobiles', '7', '', 'Jerd Nathan', '15', 'Dretio Jacqueline', '13', 'Blangero Olivier', '01', 'Kenobi Obiwan', '4', 'Di Nicolo Hervé', '7'],
            ['0008', '2018-2019', 'Course de Foix', 'Modèles réduits automobiles', '8', '', 'Hernandez Lucas', '11', 'Blangero Olivier', '1', 'Dretio Jacqueline', '13', 'Perez Jean-Luc', '3', 'Skywalker Luke', '5'],
            ['0009', '2018-2019', 'Course d\'Alès', 'Modèles réduits automobiles', '9', '', 'Kenobi Obiwan', '4', 'Nougaro Charles', '8', 'Grimault Fabien', '17', 'Grimault Lucas', '16', 'Skywalker Luke', '5'],
            ['0010', '2018-2019', 'Course de Perpignan', 'Modèles réduits automobiles', '10', '', 'Skywalker Luke', '5', 'Blangero Olivier', '1', 'Faure Sébatien', '2', 'Di Nicolo Hervé', '7', 'Kasparov Garry', '6'],
            ['0011', '2018-2019', 'Course de Blagnac', 'Drones à 3 rotors', '1', '1', 'Blangero Olivier', '1', 'Perez Jean-Luc', '3', 'Hernandez Lucas', '11', 'Nougaro Charles', '8', 'Skywalker Luke', '5'],
            ['0012', '2018-2019', 'Course de Blagnac', 'Drones à 3 rotors', '1', '2', 'Blangero Olivier', '1', 'Krit John', '10', 'Kasparov Garry', '6', 'Perez Jean-Luc', '3', 'Kenobi Obiwan', '4'],
            ['0013', '2018-2019', 'Course de Blagnac', 'Drones à 3 rotors', '1', '3', 'Blangero Olivier', '1', 'Perez Jean-Luc', '3', 'Krit John', '10', 'Di Nicolo Hervé', '7', 'Kasparov Garry', '6'],
            ['0014', '2018-2019', 'Course de Béziers', 'Drones à 3 rotors', '2', '1', 'Kasparov Garry', '6', 'Blangero Olivier', '1', 'Serti Olivia', '14', 'Nougaro Charles', '8', 'Di Nicolo Hervé', '7'],
            ['0015', '2018-2019', 'Course de Béziers', 'Drones à 3 rotors', '2', '2', 'Brel Jacques', '9', 'Skywalker Luke', '5', 'Grimault Fabien', '17', 'Di Nicolo Hervé', '7', 'Kenobi Obiwan', '4'],
            ['0016', '2018-2019', 'Course de Béziers', 'Drones à 3 rotors', '2', '3', 'Nougaro Charles', '8', 'Perez Jean-Luc', '3', 'Blangero Olivier', '01', 'Brel Jacques', '9', 'Kenobi Obiwan', '4'],
            ['0017', '2018-2019', 'Course de St-Estève', 'Drones à 3 rotors', '3', '1', 'Jerd Nathan', '15', 'Dretio Jacqueline', '13', 'Blangero Olivier', '01', 'Kenobi Obiwan', '4', 'Di Nicolo Hervé', '7'],
            ['0018', '2018-2019', 'Course de St-Estève', 'Drones à 3 rotors', '3', '2', 'Hernandez Lucas', '11', 'Blangero Olivier', '1', 'Dretio Jacqueline', '13', 'Perez Jean-Luc', '3', 'Skywalker Luke', '5'],
            ['0019', '2018-2019', 'Course de St-Estève', 'Drones à 3 rotors', '3', '3', 'Kenobi Obiwan', '4', 'Nougaro Charles', '8', 'Grimault Fabien', '17', 'Grimault Lucas', '16', 'Skywalker Luke', '5'],
            ['0020', '2018-2019', 'Course de Mende', 'Drones à 3 rotors', '4', '1', 'Skywalker Luke', '5', 'Blangero Olivier', '1', 'Faure Sébatien', '2', 'Di Nicolo Hervé', '7', 'Kasparov Garry', '6'],
            ['0021', '2018-2019', 'Course de Mende', 'Drones à 3 rotors', '4', '2', 'Blangero Olivier', '1', 'Perez Jean-Luc', '3', 'Hernandez Lucas', '11', 'Nougaro Charles', '8', 'Skywalker Luke', '5'],
            ['0022', '2018-2019', 'Course de Mende', 'Drones à 3 rotors', '4', '3', 'Blangero Olivier', '1', 'Krit John', '10', 'Kasparov Garry', '6', 'Perez Jean-Luc', '3', 'Kenobi Obiwan', '4'],
            ['0023', '2018-2019', 'Course de Mirepoix', 'Drones à 3 rotors', '5', '1', 'Blangero Olivier', '1', 'Perez Jean-Luc', '3', 'Krit John', '10', 'Di Nicolo Hervé', '7', 'Kasparov Garry', '6'],
            ['0024', '2018-2019', 'Course de Mirepoix', 'Drones à 3 rotors', '5', '2', 'Kasparov Garry', '6', 'Blangero Olivier', '1', 'Serti Olivia', '14', 'Nougaro Charles', '8', 'Di Nicolo Hervé', '7'],
            ['0025', '2018-2019', 'Course de Mirepoix', 'Drones à 3 rotors', '5', '3', 'Brel Jacques', '9', 'Skywalker Luke', '5', 'Grimault Fabien', '17', 'Di Nicolo Hervé', '7', 'Kenobi Obiwan', '4'],
            ['0026', '2019-2020', 'Course de Toulouse', 'Drones à 3 rotors', '1', '1', 'Nougaro Charles', '8', 'Perez Jean-Luc', '3', 'Blangero Olivier', '01', 'Brel Jacques', '9', 'Kenobi Obiwan', '4'],
            ['0027', '2019-2020', 'Course de Toulouse', 'Drones à 3 rotors', '1', '2', 'Jerd Nathan', '15', 'Dretio Jacqueline', '13', 'Blangero Olivier', '01', 'Kenobi Obiwan', '4', 'Di Nicolo Hervé', '7'],
            ['0028', '2019-2020', 'Course de Toulouse', 'Drones à 3 rotors', '1', '3', 'Hernandez Lucas', '11', 'Blangero Olivier', '1', 'Dretio Jacqueline', '13', 'Perez Jean-Luc', '3', 'Skywalker Luke', '5'],
            ['0029', '2019-2020', 'Course d\'Alès', 'Drones à 4 rotors', '1', '1', 'Kenobi Obiwan', '4', 'Nougaro Charles', '8', 'Grimault Fabien', '17', 'Grimault Lucas', '16', 'Skywalker Luke', '5'],
            ['0030', '2019-2020', 'Course d\'Alès', 'Drones à 4 rotors', '1', '2', 'Skywalker Luke', '5', 'Blangero Olivier', '1', 'Faure Sébatien', '2', 'Di Nicolo Hervé', '7', 'Kasparov Garry', '6'],
            ['0031', '2019-2020', 'Course d\'Alès', 'Drones à 4 rotors', '1', '3', 'Skywalker Luke', '5', 'Serti Olivia', '14', 'Faure Sébatien', '2', 'Blangero Olivier', '1', 'Nougaro Charles', '8'],
        ];

        foreach($default_table_competitions as $table) {
            $wpdb->insert("{$wpdb->prefix}modelisme_competitions", array(
                'competition_number' => $table[0],
                'season' => $table[1],
                'name' => $table[2],
                'domain' => $table[3],
                'race_number' => $table[4],
                'round_number' => $table[5],
                'first_member_name' => $table[6],
                'first_member_id' => $table[7],
                'second_member_name' => $table[8],
                'second_member_id' => $table[9],
                'third_member_name' => $table[10],
                'third_member_id' => $table[11],
                'fourth_member_name' => $table[12],
                'fourth_member_id' => $table[13],
                'fifth_member_name' => $table[14],
                'fifth_member_id' => $table[15]
            ));
        }
    }

    public function countClubs() {
        global $wpdb;
        $res = $wpdb->get_results("SELECT COUNT(id) as total FROM {$wpdb->prefix}modelisme_clubs", ARRAY_A);
        return $res[0]['total'];
    }

    public function countMembers() {
        global $wpdb;
        $res = $wpdb->get_results("SELECT COUNT(id) as total FROM {$wpdb->prefix}modelisme_members", ARRAY_A);
        return $res[0]['total'];
    }

    public function countMembersByClubs() {
        global $wpdb;
        $res = $wpdb->get_results("SELECT COUNT({$wpdb->prefix}modelisme_members.id) AS total, ".
            "{$wpdb->prefix}modelisme_clubs.name AS club_name FROM {$wpdb->prefix}modelisme_clubs ".
            "LEFT JOIN {$wpdb->prefix}modelisme_members ON {$wpdb->prefix}modelisme_clubs.id = {$wpdb->prefix}modelisme_members.club_id ".
            "GROUP BY {$wpdb->prefix}modelisme_clubs.id;", ARRAY_A);
        return $res;
    }

    public function calculateRatings( $season, $domain, $limit = null ) {
        global $wpdb;

        $points = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}modelisme_ratings WHERE domain='".$domain."'", ARRAY_A);

        $competitions = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}modelisme_competitions WHERE season='".$season."' AND domain='".$domain."'", ARRAY_A);

        $result = [];

        foreach( $competitions as $competition ) {
            $result[$competition['first_member_name']] += (int) $points[0]['first'];
            $result[$competition['second_member_name']] += (int) $points[0]['second'];
            $result[$competition['third_member_name']] += (int) $points[0]['third'];
            $result[$competition['fourth_member_name']] += (int) $points[0]['fourth'];
            $result[$competition['fifth_member_name']] += (int) $points[0]['fifth'];
        }

        arsort($result);

        if( ! is_null( $limit ) ) {
            $result= array_slice($result, 0, $limit);
        }

        return $result;
    }

}