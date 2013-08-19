<?php
class PDF extends FPDF {
  function Header() {
    $this->AddFont('Glyphish');
    $this->AddFont('Zocial');
    $this->AddFont('DroidSans');
    $this->AddFont('DroidSans', 'B');
    $this->SetFont('DroidSans', 'B', 10);
    
    $this->SetFillColor(51,51,51);
    $this->SetTextColor(210,210,210);
    $this->Cell(195, 10, variable_get('site_name') . ' Performance Summary ' . date('m/d/Y', time() - 60 * 60 * 24 * 7) . ' to ' . date('m/d/Y', time() - 60 * 60 * 24), 0, 2, 'C', 'true');
    
    $this->SetCreator('Theory Communication & Design');
    $this->SetAuthor('Albert Martin (574-310-4391)');
    $this->SetTitle('The BLOCK 7 Day Digest ' . date('m/d/Y', time() - 60 * 60 * 24 * 8) . ' to ' . date('m/d/Y', time() - 60 * 60 * 24));
    $this->SetSubject('The BLOCK Performance Report');
    $this->SetCompression(1);
    
    $this->SetFillColor(241, 241, 241);
    $this->Rect(10, 20, 195, 30, 'F');
    $this->Image('/web/theblock/sites/all/themes/theblock/logo-print.png', 15, 26, 75);
  }
  function Footer() {
    $this->SetFont('DroidSans', '', 8);
    
    $this->SetFillColor(51,51,51);
    $this->SetTextColor(210,210,210);
    $this->SetY(-20);
    $this->Cell(195, 10, 'Data prepared on ' . date('m/d/Y') . ' by Theory Communication & Design', 0, 2, 'C', 'true');
    
    $this->Image('/web/theblock/sites/all/modules/sevenday/theorylogo-white.png', 10, 260, 11);
    $this->Image('/web/theblock/sites/all/modules/sevenday/theorytype-white.png', 182, 261.5, 21);
  }
  function SetupHeaderPrinting() {
    $this->SetTextColor(171,171,171);
    $this->SetDrawColor(241, 241, 241);
    $this->SetLineWidth(1);
    $this->SetFont('DroidSans', '', 20);
  }
  function PrintIcons($section) {
    $this->SetFont('Glyphish', '', 50);
    
    switch ($section) {
      case 0:
        $this->SetTextColor(236, 175, 86);
        $this->Text(14,82,'}');
        $this->SetTextColor(113, 162, 97);
        $this->Text(56,83,'d');
        $this->SetTextColor(62, 109, 156);
        $this->Text(95,82,'µ');
        break;
      case 1:
        $this->SetTextColor(129, 86, 138);
        $this->Text(14,122,'B');
        $this->SetTextColor(113, 162, 97);
        $this->Text(76,122,'L');
        $this->SetTextColor(62, 109, 156);
        $this->Text(14,143,'o');
        $this->Text(76,144,'i');
        break;
      case 2:
        $this->SetTextColor(59, 89, 146);
        $this->Text(87, 186,'L');
        $this->SetFont('Zocial', '', 30);
        $this->SetTextColor(59, 89, 146);
        $this->Text(19, 183,'f');
        $this->SetTextColor(220, 54, 43);
        $this->Text(21, 206,'G');
        $this->SetTextColor(89, 208, 237);
        $this->Text(90, 206,'T');
        break;
    }
  }
  function PrintHealthMeters($userscore, $visitorscore, $socialscore) {
    $avg = (($userscore + $visitorscore + $socialscore) / 3);
    $this->Image('https://chart.googleapis.com/chart?chxl=1:|Under|Over&chdl=Performance&chl=+&chxt=x,y&chf=bg,s,F0F0F0&chs=300x150&cht=gm&chco=333333,C64445|EBAE55|70A160&chd=t:' . $avg . '&chdlp=b&chma=|0,5#.png', 161, 22, 43);
    $this->Image('https://chart.googleapis.com/chart?chf=bg,s,FFFFFF&chs=250x150&cht=gm&chco=333333,C64445|EBAE55|70A160&chd=t:' . $userscore . '&chdlp=b&chma=|0,5#.png', 10, 53, 15);
    $this->Image('https://chart.googleapis.com/chart?chf=bg,s,FFFFFF&chs=250x150&cht=gm&chco=333333,C64445|EBAE55|70A160&chd=t:' . $visitorscore . '&chdlp=b&chma=|0,5#.png', 10, 91, 15);
    $this->Image('https://chart.googleapis.com/chart?chf=bg,s,FFFFFF&chs=250x150&cht=gm&chco=333333,C64445|EBAE55|70A160&chd=t:' . $socialscore . '&chdlp=b&chma=|0,5#.png', 10, 154, 15);
    
    $this->SetFont('DroidSans', '', 16);
    $this->SetTextColor(171,171,171);
    $this->Text(128,37,'7 DAY DIGEST');
  }
  function PrintSectionHeaders() {
    $this->SetupHeaderPrinting();
    $this->Text(26,61,'User Actions');
    $this->Line(69,59,204.5,59);
    $this->Text(25.5,99,'Visitor Overview');
    $this->Line(81,97,204.5,97);
    $this->Text(26,162,'Social Overview');
    $this->Line(79,160,204.5,160);
    $this->Text(11,224,'Observations on This Period');
    $this->Line(103,222,204.5,222);
  }
  function PrintUserActions($registered, $posts, $forum, $charturl) {
    $this->PrintIcons(0);
    $this->SetFont('DroidSans', '', 30);
    $this->SetTextColor(236, 175, 86);
    $this->Text(35, 80, $registered);
    $this->SetTextColor(113, 162, 97);
    $this->Text(75, 80, $posts);
    $this->SetTextColor(62, 109, 156);
    $this->Text(115, 80, $forum);
    
    $this->SetFont('DroidSans', 'B', 8);
    $this->SetTextColor(51,51,51);
    $this->Text(35,84,'New Registered');
    $this->Text(75,84,'New Posts');
    $this->Text(115,84,'In the Forum');
    
    $this->Image($charturl, 141, 62, 65);
  }
  function PrintVisitorOverview($articles, $impressions, $visitlength, $mobile, $dailycharturl, $hourlycharturl) {
    $this->PrintIcons(1);        
    $this->SetFont('DroidSans', '', 30);
    $this->SetTextColor(129, 86, 138);
    $this->Text(34,120,number_format($articles));
    $this->SetTextColor(113, 162, 97);
    $this->Text(95,120,number_format($impressions));
    $this->SetTextColor(62, 109, 156);
    $this->Text(34,141,$visitlength);
    $this->Text(95,141,$mobile . '%');
    
    $this->SetFont('DroidSans', 'B', 8);
    $this->SetTextColor(51,51,51);
    $this->Text(34,124,'Articles Read');
    $this->Text(95,124,'Total Impressions');
    $this->Text(34,145,'Average Visit Length');
    $this->Text(95,145,'Mobile Visitors');
    
    $this->Image($dailycharturl, 141, 103, 65);
    $this->Image($hourlycharturl, 141, 136, 65);
  }
  function PrintSocialOverview($facebookimpressions, $facebookpageviews, $searchimpressions, $twitterpageviews) {
    $this->PrintIcons(2); 
    $this->SetFont('DroidSans', '', 30);
    $this->SetTextColor(59, 89, 146);
    $this->Text(34,184,number_format($facebookimpressions));
    $this->Text(107,184,number_format($facebookpageviews));
    $this->SetTextColor(220, 54, 43);
    $this->Text(34,206,number_format($searchimpressions));
    $this->SetTextColor(89, 208, 237);
    $this->Text(107,206,number_format($twitterpageviews));
    
    $this->SetFont('DroidSans', 'B', 8);
    $this->SetTextColor(51,51,51);
    $this->Text(34,188,'Post Impressions');
    $this->Text(107,188,'Pageviews from Facebook');
    $this->Text(34,210,'Search Impressions');
    $this->Text(107,210,'Twitter Pageviews');
  }
  function PrintTopReferrers($referrers) {
    $this->SetFillColor(241, 241, 241);
    $this->SetTextColor(51,51,51);
    $this->SetFont('DroidSans', 'B', 8);
    $this->SetXY(155,165);
    $this->SetFillColor(51,51,51);
    $this->SetTextColor(255,255,255);
    $this->Cell(50,4,'Top 5 Referrers - Pageviews',0,2,'C','true');
    $this->SetFillColor(241, 241, 241);
    $this->SetTextColor(51,51,51);
    $this->SetFont('DroidSans', '', 7);
    $this->Cell(50,4,$referrers[0],0,2,'C');
    $this->Cell(50,4,$referrers[1],0,2,'C','true');
    $this->Cell(50,4,$referrers[2],0,2,'C');
    $this->Cell(50,4,$referrers[3],0,2,'C','true');
    $this->Cell(50,4,$referrers[4],0,2,'C');
  }
  function PrintTopStory($topstory) {
    $article_path = drupal_get_normal_path(ltrim($topstory['pagePath'],'/'));
    $article = menu_get_object('node', 1, $article_path);
    
    $this->Image(image_style_path('600x250', $article->field_image['und'][0]['uri']), 155, 192, 50);
    $this->SetXY(155,215);
    $this->SetFont('DroidSans', 'B', 7);
    $this->Cell(50,1,'Most Read Article - ' . number_format($topstory['pageviews']) . ' reads',0,0,'C');
  }
  function PrintObservations() {
    $this->SetTextColor(241, 241, 241);
    $this->SetFont('Glyphish', '', 155);
    $this->Text(160,262.5,'(');
    
    $this->SetFont('DroidSans', '', 9.5);
    $this->SetTextColor(171,171,171);
    $this->Text(11,66,variable_get('sevenday_observations_user'));
    $this->Text(11,104,variable_get('sevenday_observations_visitor'));
    $this->Text(11,167,variable_get('sevenday_observations_social'));
    
    $this->SetFont('DroidSans', '', 10);
    $this->SetTextColor(51, 51, 51);
    $this->Text(11,232,'- ' . variable_get('sevenday_observations_period1'));
    $this->Text(11,239,'- ' . variable_get('sevenday_observations_period2'));
    $this->Text(11,246,'- ' . variable_get('sevenday_observations_period3'));
    $this->Text(11,253,'- ' . variable_get('sevenday_observations_period4'));
  }
  function AcceptPageBreak() {
    return false;
  }
}
