<?php
class CustomtimeHelper extends AppHelper {
    public $helpers = array('Html', 'Time');

    public $days = array(
        1 => array(
            'long' => 'Lunedì',
            'short' => 'Lun'
        ),
        2 => array(
            'long' => 'Martedì',
            'short' => 'Mar'
        ),
        3 => array(
            'long' => 'Mercoledì',
            'short' => 'Mer'
        ),
        4 => array(
            'long' => 'Giovedì',
            'short' => 'Gio'
        ),
        5 => array(
            'long' => 'Venerdì',
            'short' => 'ven'
        ),
        6 => array(
            'long' => 'Sabato',
            'short' => 'Sab'
        ),
        7 => array(
            'long' => 'Domenica',
            'short' => 'Dom'
        )
    );
    
    public $months = array(
        1 => array(
            'long' => 'Gennaio',
            'short' => 'Gen'
        ),
        2 => array(
            'long' => 'Febbraio',
            'short' => 'Feb'
        ),
        3 => array(
            'long' => 'Marzo',
            'short' => 'Mar'
        ),
        4 => array(
            'long' => 'Aprile',
            'short' => 'Apr'
        ),
        5 => array(
            'long' => 'Maggio',
            'short' => 'Mag'
        ),
        6 => array(
            'long' => 'Giugno',
            'short' => 'Giu'
        ),
        7 => array(
            'long' => 'Luglio',
            'short' => 'Lug'
        ),
        8 => array(
            'long' => 'Agosto',
            'short' => 'Ago'
        ),
        9 => array(
            'long' => 'Settembre',
            'short' => 'Set'
        ),
        10 => array(
            'long' => 'Ottobre',
            'short' => 'Ott'
        ),
        11 => array(
            'long' => 'Novembre',
            'short' => 'Nov'
        ),
        12 => array(
            'long' => 'Dicembre',
            'short' => 'Dic'
        ),
    );
    
    public $settings = array();
    
    public function format($time, $settings = array()) 
    {   
        
        $this->setProperties($time, $settings);        
        if ($this->Time->isToday($time)){
            return 'Oggi'.$this->settings['hoursMinutes'];
        }
        if ($this->Time->wasYesterday($time)){
            return 'Ieri'.$this->settings['hoursMinutes'];
        }
        if ($this->Time->isThisMonth($time)){
            return $this->settings['dayLetters'].' '.$this->settings['dayNumbers']. ' ' .$this->settings['monthLetters'].$this->settings['hoursMinutesPast'];
        }
        if ($this->Time->isThisYear($time)){
            return $this->settings['dayNumbers']. ' '.$this->settings['monthLetters'];    
        }
        return $this->settings['dayNumbers'].'/'.$this->settings['monthNumbers'].'/'.$this->settings['year'];
        
    }                
    
    public function timeAgo($time, $settings = array())
    {
        $this->setProperties($time, $settings);
        if ($this->Time->isFuture($time)){
            return $this->Time->format('d/m/Y', $time);
        }
        
        if ($this->Time->isToday($time)){
            if (date('H') == $this->settings['hours']){
                $minutesAgo = date('i') - $this->settings['minutes'];
                return $minutesAgo . ' minuti fa';    
            }
            
            $hourAgo = date('H') - $this->settings['hours'];
            if ($hourAgo <= 6){
                return $hourAgo . ' ore fa';    
            }
            
            return 'Oggi'.$this->settings['hoursMinutes'];
        }
        
        return $this->format($time, $settings);
    }
    
    public function timeLeft($time, $settings = array())
    {   
        $this->setProperties($time, $settings);
        if ($this->Time->isTomorrow($time)){
            return 'Domani'.$this->settings['hoursMinutes'];
        }
        
        if ($this->Time->isToday($time)){            
            return 'Oggi'.$this->settings['hoursMinutes'];
        }
        
        if (!$this->Time->isFuture($time)) return $this->Time->format('d/m/Y', $time);
        
        if ($this->Time->isThisMonth($time)){
            $daysLeft = $this->settings['dayNumbers'] - date('d');
            return 'Tra '.$daysLeft.' giorni'.$this->settings['hoursMinutes'];
        }        
        
        return $this->settings['dayNumbers'].'/'.$this->settings['monthNumbers'].'/'.$this->settings['year'];
        
    }
    
    private function setProperties($time, $settings)
    {
        //set a day information
        $type = (isset($settings['type'])) ? $settings['type'] : 'long';
        $this->settings['day']              = $this->Time->format('N', $time);
        $this->settings['dayLetters']       = $this->days[$this->settings['day']][$type];
        $this->settings['dayNumbers']       = $this->Time->format('d', $time);
        
        //set a month information
        $this->settings['month']            = $this->Time->format('n', $time);
        $this->settings['monthLetters']     = $this->months[$this->settings['month']][$type];
        $this->settings['monthNumbers']     = $this->Time->format('m', $time);
        
        //set a y information
        $this->settings['hours']            = $this->Time->format('H', $time);
        $this->settings['minutes']          = $this->Time->format('i', $time);
        $this->settings['hoursMinutes']     = (isset($settings['showHour']) && !$settings['showHour']) ? '' : ' alle '. $this->Time->format('H:i', $time);
        $this->settings['hoursMinutesPast'] = (isset($settings['showHourPast']) && !$settings['showHourPast']) ? '' : ' alle '. $this->Time->format('H:i', $time);
        $this->settings['year']             = $this->Time->format('Y', $time);
    }

    
}