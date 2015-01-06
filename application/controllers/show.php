<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Show extends CI_Controller {
    public function index() {
   		$this->gallery();     
    }

   	public function gallery($page = 1, $order = "old") {
		$this->load->library("table");
    	$this->load->model("Comic");
      	$this->load->model("DB_Instance");

        $query = $this->db->query('SELECT WEEKDAY(CURRENT_DATE())');
        $today = $query->result();
        // var_dump($today);

        // $update_time = $query->result();
        // $update_time = $update_time[0]->update_time;
        // $query = $this->db->query('SELECT WEEKDAY(FROM_UNIXTIME('.$update_time.'))');
        // $weekday = $query->result();

        // if: today is Monday, check for refresh
        // if ($today == 0) {
        //     $db_info = $this->db->get("db_info", "id = 1");

        //     // if: it has been over 6 days since last update, update 
        //     if ((time() - $db_info->update_time) >= 518400) {
        //         echo "EENT";
        //     }
        // } 

        // else {
        //     $db_info = $this->db->get("db_info");
        //     echo '<pre>';
        //     var_dump($db_info);
        //     echo '</pre>';
        //     echo time() - $db_info[0]->update_time;


        // if: database hasn't been updated in a week, refresh
        // if (time() - $this->db->get("db_info", array("id" => 1))) { 

        // $instance = $this->db->get("db_info", array("id" => 1));
        // $result = $instance->result();

        // $this->Comic->load($result[0]->comic_count);
        // var_dump($result[0]->update_time);
        // } 

        // else {
            $data["comics"] = $this->Comic->get();
          	$data["order"] = $order;
            $data["page"] = $page;
            
          	$this->load->view("templates/header");
          	$this->load->view("gallery", $data);
        	$this->load->view("templates/footer");
        // }
   	}

   	// admin only: insert/update comic entry to database
    public function add() {
    	$this->load->helper("form");
        $this->load->model("Comic");
    	$comics = $this->Comic->get();
    	$topic_form_options = array();
    	
    	foreach ($comics as $id => $comic) {
    		$topic_form_options[$id] = $comic->topic;
    	}

    	$this->load->library("form_validation");
    	$this->form_validation->set_rules(array(
    		array(
    			'field' => 'number', 
    			'label' => 'Number', 
    			'rules' => 'required|is_numeric'),
    		array(
    			'field' => 'title', 
    			'label' => 'Title', 
    			'rules' => 'required'),
	    	array(
    			'field' => 'date', 
    			'label' => 'Date', 
    			'rules' => 'required|callback_date_validation'),
    	));
		
		$this->form_validation->set_error_delimiters('<div class="alert alert-error">','</div>');

        // process form (if sent)
        $this->load->view("templates/header");
		if ($this->form_validation->run()) {		// if: form has been sent and validated, show success messsage
            $this->load->view("insert_form_success");
			$this->Comic->number = $this->input->post("number");
			$this->Comic->date = $this->input->post("date");
			
			// set, or create, topic
			if ($this->input->post("radio-topic")) {
				$this->Comic->topic = $this->input->post("new_topic");
			}
			else {
				$this->Comic->topic = $this->input->post("topic");
			}
			
			$this->Comic->title = $this->input->post("title");
			$this->Comic->img_src = $this->input->post("img_src");
			$this->Comic->save();
		}
		
        // display form
        $this->load->view("insert_form", array(
    			"topic_form_options" => $topic_form_options,
    	));

    	$this->load->view("templates/footer");
    }	

    public function comic($id) {
    	$this->load->model("Comic");
        
        if ($id > sizeof($this->Comic->get()) || $id === "latest") { 
            echo '<script type="text/javascript">window.location.replace("'.sizeof($this->Comic->get()).'")</script>'; 
        }
        
        else if ($id < 1 || $id === "oldest") {
            echo '<script type="text/javascript">window.location.replace("1")</script>'; 
        }
        
        else {
            $this->Comic->load($id);
            $data["comic_count"] = sizeof($this->Comic->get());
            $data["comic"] = $this->Comic;
        	
            $this->load->view("templates/header");
            $this->load->view("comic", $data);
            $this->load->view("templates/footer");
        }
    }

    public function test() {
        $this->load->model("DB_Instance"); 
        $this->load->model("Comic");       

        $instance = $this->db->get("db_info", array("id" => 1));
        $result = $instance->result();

        $this->Comic->load($result[0]->comic_count);
        var_dump($result[0]->update_time);
    }

    // parses main site for updates, populating database with new entries
    public function refresh() {
        $this->load->model("DB_Instance"); 
        $this->load->model("Comic");       

        // grab database info
        $instance = $this->db->get("db_info", array("id" => 1));
        $result = $instance->result();    	
        $latest_update = $result[0]->update_time;
        $comic_count = $result[0]->comic_count;
        $out_of_date = TRUE;         // true: the latest comic isn't in the database

        $URL = "http://www.awkwardzombie.com/index.php?page=1";
        $outputFile = "directory.txt";
    	$ch = curl_init($URL);
        $fp = fopen($outputFile, "w");

        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        
        $DOM = new DOMDocument();
    	$DOM->loadHTML(file_get_contents($outputFile));
    	$tabledata = $DOM->getElementsByTagName('td');
    	$comics = [];
        $i = 0;
        
        foreach ($tabledata as $entry) {

            if ($out_of_date) {
                // remove extraneous links
                switch ($entry->nodeValue) {
                    case 'COMIC':
                        break;
                    case 'ARCHIVE':
                        break;
                    case 'FLASH':
                        break;
                    case 'STORE':
                        break;
                    case 'FORUM':
                        break;  
                    case 'FAQ':
                        break;        
                    case 'FEED':
                        break; 
                    case 'DONATE':
                        break; 
                    case 'Show All Comics':
                        break;
                    case 'Privacy Policy':
                        break;
                    case 'advertising with AZ':
                        break;      
                    default:  
                        $str = $entry->nodeValue;

                        // if: string containing number and date, split string into values
                        if ($i === 0) {
                            // remove '#'
                            $str_array = explode("#", $str, 2);
                            $str = $str_array[1];

                            // get comic number
                            $str_array = explode(",", $str, 2);
                            $number = $str_array[0];
                            $str = $str_array[1];

                            // get date, remove colon
                            $str_array = explode(":", $str, 2);
                            $date = $str_array[0];
                            $date_array = explode("-", $date);
                            $month_array = explode(" ", $date_array[0]);
                            $date = date("20".$date_array[2]."-".$month_array[1]."-".$date_array[1]);

                            // if: number matches most recent database entry, stop updating and return user to gallery
                            if ($number <= $comic_count) {
                                $out_of_date = FALSE;
                            }


                            $i++;
                        }

                        // if: string contains title, add to title, follow link to comic, and grab image url
                        else if ($i === 1) {
                            $title = $entry->nodeValue;

                            $comic_url = "http://www.awkwardzombie.com/" . $entry->childNodes->item(0)->attributes->item(0)->value;
                            $comicHTMLFile = "comicHTML.txt";
                            $ch = curl_init($comic_url);
                            $fp = fopen($comicHTMLFile, "w");

                            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
                            curl_setopt($ch, CURLOPT_FILE, $fp);
                            curl_setopt($ch, CURLOPT_HEADER, 0);

                            curl_exec($ch);
                            curl_close($ch);
                            fclose($fp);
                            
                            $comicDOM = new DOMDocument;
                            $comicDOM->loadHTML(file_get_contents($comicHTMLFile));
                            $imgs = $comicDOM->getElementsByTagName('img');
                            foreach ($imgs as $img) {
                                if  (preg_match('/amazonaws/', $img->attributes->item(0)->textContent) ||
                                    preg_match('/photobucket/', $img->attributes->item(0)->textContent) ) {  // if: aws or photobucket url
                                    $img_src = $img->attributes->item(0)->textContent;
                                }
                            }
                            
                            $i++;
                        } 

                        // add to game/topic
                        else {
                            $topic = $entry->nodeValue;
                            if ($topic === "") { $topic = "(misc)"; }
                            $i = 0;

                            $newComic = new Comic();
                            $newComic->number = $number;
                            $newComic->date = $date;
                            $newComic->topic = $topic;
                            $newComic->title = $title;
                            $newComic->img_src = $img_src;
                            $comics[] = $newComic;
                            // var_dump($newComic);
                        }
                        break;
                    
    	       }
            }
            
            else {      // else: stop updating
                break;
            }
        }

        // save new comics, if any
        for ($i=1; $i < sizeof($comics); $i++) { 
            $newComic = $comics[sizeof($comics) - $i];
            $newComic->save();
        }
        if (isset($comics[0])) {
            $newComic = $comics[0];
            $newComic->save();
        }
        
        // update comic count
        $this->db->update("db_info", 
        array(
            "update_time" => time(), 
            "comic_count" => $comic_count + sizeof($comics)
            ), 
        "id = 1");   
    }


    // returns true if string is in valid date format
    public function string_validation($string) {
    	$date = explode("-",$string);
    	if (!@checkdate($date[1], $date[0], $date[2])) {
    		return FALSE;
    	}
    	return TRUE;
    }
}