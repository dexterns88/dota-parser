<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Theme_view {

  var $theme;
  var $colors = array();
  var $names = array();

  public function render( $replay , $file )
  {

    $this->theme['version'] = '1.' . sprintf('%02d', $replay->header['major_v']);
    $this->theme['title'] = $replay->extra['title'];
    $this->theme['game_host'] = isset($replay->game['creator']) ? $replay->game['creator'] : "n/a";
    $this->theme['game_saver'] = isset($replay->game['saver_name']) ? $replay->game['saver_name'] : "n/a";
    $this->theme['player_count'] = isset($replay->game['player_count']) ? $replay->game['player_count'] : "n/a";
    $this->theme['time'] = convert_time($replay->header['length']);
    $this->theme['game_observer'] = $replay->game['observers'];
    $this->theme['map'] = $replay->game['map'];
    $this->theme['winner'] = $replay->extra['winner'];

    $this->theme['download']['link'] = $replay->extra['original_filename'];
    $this->theme['download']['original_name'] = $file;

    $this->theme['description'] = $replay->extra['text'];
    //bans_num
    $this->getBans( $replay , $file );

    // team creator
    $this->getTeam( $replay , $file );

    $this->getChat( $replay );

    return $this->theme;
  }

  private function getChat( $replay )
  {

    foreach ($replay->chat as $key=>$content) {
      $prev_time = $content['time'];
      $this->theme['chat'][$key]['time'] = convert_time($content['time']);


      if (isset($content['mode']) && isset($this->colors[$content['mode']]) && isset($this->names[$content['mode']])) {
        if (is_int($content['mode'])) {
          $this->theme['chat'][$key]['mode'] = ' / '.($content['mode'] == 0x01 ? "Allied" : "");
        }
        else {
          $this->theme['chat'][$key]['mode'] = ' / '.$content['mode'];
        }
      }



      if (isset($content['player_id'])) {
        // no color for observers
        if (isset($this->colors[$content['player_id']]))
        {
          $this->theme['chat'][$key]['user']['color'] = $this->colors[$content['player_id']];
        }
        else
        {
          $this->theme['chat'][$key]['user']['color'] = 'observer';
        }
        $this->theme['chat'][$key]['user']['name'] = $content['player_name'];
      }

      $this->theme['chat'][$key]['text'] = htmlspecialchars($content['text'], ENT_COMPAT, 'UTF-8');

    }//end foreach

  }

  private function getTeam( $replay , $file )
  {

    for($i = 0; $i < 2; $i++ ) {
      $team = ($i == 0 ? "Sentinel" : "Scourge");
      //$this->theme['team'][$i]['race'] = $team;

      $countPlayer = 0;
      foreach($replay->teams[$i] as $pid=>$player) {

        if ( isset($replay->ActivatedHeroes) ) {
          if ( $replay->stats[$player['dota_id']]->getHero() == false ) continue;

          $t_heroName = $replay->stats[$player['dota_id']]->getHero()->getName();

          // Set level
          $player['heroes'][$t_heroName]['level'] = $replay->stats[$player['dota_id']]->getHero()->getLevel();

          $t_heroSkills = $replay->stats[$player['dota_id']]->getHero()->getSkills();

          // Convert skill array to old format
          foreach ( $t_heroSkills as $time => $skill ) {
            $player['heroes'][$t_heroName]['abilities'][$time] = $skill;
          }

          $player['heroes'][$t_heroName]['data'] = $replay->stats[$player['dota_id']]->getHero()->getData();
        } // end if activated

        foreach($player['heroes'] as $name=>$hero) {

          if( $name == "order" || !isset($hero['level'])) continue;

          if( $name != "Common" ) {
            // Merge common skills and atribute stats with Hero's skills
            if(isset($player['heroes']['Common']) ) {

              $hero['level'] += $player['heroes']['Common']['level'];
              $hero['abilities'] = array_merge($hero['abilities'], $player['heroes']['Common']['abilities']);
            }
            if ( $hero['level'] > 25 ) {
              $hero['level'] = 25;
            }
            @ksort($hero['abilities']);
            $p_hero = $hero;

            break;
          }

        } // foreach name hero

        $this->theme['teams'][$i][$countPlayer]['player_id'] = $player['player_id'];
        $this->theme['teams'][$i][$countPlayer]['image'] = $p_hero['data']->getArt();
        $this->theme['teams'][$i][$countPlayer]['player_name'] = $player['name'];
        $this->theme['teams'][$i][$countPlayer]['hero_name'] = $p_hero['data']->getName();
        $this->theme['teams'][$i][$countPlayer]['level'] = $p_hero['level'];
        $this->theme['teams'][$i][$countPlayer]['apm'] = round( (60 * 1000 * $player['apm']) / ($player['time']));

        if(isset($replay->stats[$player['dota_id']])) {
          $stats = $replay->stats[$player['dota_id']];

          $this->theme['teams'][$i][$countPlayer]['stat_hero']['kills'] = $stats->HeroKills;
          $this->theme['teams'][$i][$countPlayer]['stat_hero']['deaths'] = $stats->Deaths;
          $this->theme['teams'][$i][$countPlayer]['stat_hero']['assists'] = $stats->Assists;

          $this->theme['teams'][$i][$countPlayer]['stat_creep']['kills'] = $stats->CreepKills;
          $this->theme['teams'][$i][$countPlayer]['stat_creep']['denies'] = $stats->CreepDenies;
          $this->theme['teams'][$i][$countPlayer]['stat_creep']['neutrals'] = $stats->Neutrals;
        }

        //inventory

        for($j = 0; $j < 6; $j++ ) {
          $art = ( isset($stats->Inventory[$j]) && is_object($stats->Inventory[$j]) ) ?  $stats->Inventory[$j]->getArt() : "images/BTNEmpty.gif";
          $name = ( isset($stats->Inventory[$j]) && is_object($stats->Inventory[$j]) ) ?  $stats->Inventory[$j]->getName() : "Empty";
          $this->theme['teams'][$i][$countPlayer]['inventory'][$j]['src'] = $art;
          $this->theme['teams'][$i][$countPlayer]['inventory'][$j]['name'] = $name;
        }

      // time and leave_result
        if(isset($player['time'])) {
          $playerLeaveTime = convert_time($player['time']);
        }
        else {
          $playerLeaveTime = convert_time($replay->header['length']);
        }
        if(isset($player['leave_result'])) {
          $leaveResult = $player['leave_result'];
        }
        else {
          $leaveResult = "Finished";
        }

        $this->theme['teams'][$i][$countPlayer]['leaveTime'] = $playerLeaveTime;
        $this->theme['teams'][$i][$countPlayer]['leaveReason'] = $leaveResult;

        if(isset($replay->stats[$player['dota_id']]->AA_Total) && isset($replay->stats[$player['dota_id']]->AA_Hits) && $replay->stats[$player['dota_id']]->AA_Total > 0 ) {
          $this->theme['teams'][$i][$countPlayer]['accuracy']['percent'] = round((($replay->stats[$player['dota_id']]->AA_Hits / $replay->stats[$player['dota_id']]->AA_Total)*100));
          $this->theme['teams'][$i][$countPlayer]['accuracy']['aahits'] = $replay->stats[$player['dota_id']]->AA_Hits;
          $this->theme['teams'][$i][$countPlayer]['accuracy']['aatotal'] = $replay->stats[$player['dota_id']]->AA_Total;
        }


        // skills
        unset($a_level);
        $i_skill = 0;
        $skill_counter = 0;
        foreach ($p_hero['abilities'] as $time=>$ability) {
          $i_skill++;
          if ($i_skill > 25 ) break;

          if(!isset($a_level[$ability->getName()])) {
            $a_level[$ability->getName()] = 1;
          }
          else {
            $a_level[$ability->getName()]++;
          }

          $this->theme['teams'][$i][$countPlayer]['skills'][$skill_counter]['src'] = $ability->getArt();
          $this->theme['teams'][$i][$countPlayer]['skills'][$skill_counter]['name'] = $ability->getName();
          $this->theme['teams'][$i][$countPlayer]['skills'][$skill_counter]['time'] = convert_time($time);
          $this->theme['teams'][$i][$countPlayer]['skills'][$skill_counter]['level'] = $a_level[$ability->getName()];

          $skill_counter++;

        }

        // items

        $item_counter = 0;
        foreach ($player['items'] as $time=>$item) {
          if (is_object($item) && $item->getName() != "Select Hero" ) {

            $this->theme['teams'][$i][$countPlayer]['items'][$item_counter]['src'] = $item->getArt();
            $this->theme['teams'][$i][$countPlayer]['items'][$item_counter]['name'] = $item->getName();
            $this->theme['teams'][$i][$countPlayer]['items'][$item_counter]['time'] = $time;

            $item_counter++;
          }
        }

        //actions_details
        if (isset($player['actions_details'])) {
          ksort($player['actions_details']);

          $px_per_action = 400 / $player['apm'];
          $action_counter = 0;
          foreach ($player['actions_details'] as $name=>$info) {
            $this->theme['teams'][$i][$countPlayer]['actions'][$action_counter]['name'] = $name;
            $this->theme['teams'][$i][$countPlayer]['actions'][$action_counter]['info'] = $info;
            $this->theme['teams'][$i][$countPlayer]['actions'][$action_counter]['width'] = round($info * $px_per_action);
            $action_counter++;
          }
        }

        if ($player['color']) {
          $this->colors[$player['player_id']] = $player['color'];
          $this->names[$player['player_id']] = $player['name'];
        }

        $countPlayer++; //key player

      }//end foreach teams


    }// end team creator

  }//team


  private function getBans( $replay , $file )
  {
    if( $replay->bans_num > 0 ) {

      foreach( $replay->bans as $key=>$hero ) {
        $team = ($hero->extra == 0 ? "sentinel" : "scourge");
        $this->theme['bans'][$key]['src'] = $hero->getArt();
        $this->theme['bans'][$key]['name'] = $hero->getName();
        $this->theme['bans'][$key]['team'] = $team;
      }

      foreach( $replay->picks as $key=>$hero ) {
        $team = ($hero->extra == 0 ? "sentinel" : "scourge");
        $this->theme['picks'][$key]['src'] = $hero->getArt();
        $this->theme['picks'][$key]['name'] = $hero->getName();
        $this->theme['picks'][$key]['team'] = $team;
      }

    } // bans list
  } // getBans



}