<?php

require('sector.php');

class PDF_Diag extends PDF_Sector {
    var $legends;
    var $wLegend;
    var $sum;
    var $NbVal;


    function ColumnChart($w, $h, $data, $format, $color=null, $maxVal=0, $nbDiv=4)
    {

        // RGB for color 0
        $colors[0][0] = 155;
        $colors[0][1] = 75;
        $colors[0][2] = 155;

        // RGB for color 1
        $colors[1][0] = 0;
        $colors[1][1] = 155;
        $colors[1][2] = 0;

        // RGB for color 2
        $colors[2][0] = 75;
        $colors[2][1] = 155;
        $colors[2][2] = 255;

        // RGB for color 3
        $colors[3][0] = 75;
        $colors[3][1] = 0;
        $colors[3][2] = 155;

        $this->SetFont('Courier', '', 10);
        $this->SetLegends($data,$format);

        // Starting corner (current page position where the chart has been inserted)
        $XPage = $this->GetX();
        $YPage = $this->GetY();
        $margin = 2; 

        // Y position of the chart
        $YDiag = $YPage + $margin;

        // chart HEIGHT
        $hDiag = floor($h - $margin * 2);

        // X position of the chart
        $XDiag = $XPage + $margin;

        // chart LENGHT
        $lDiag = floor($w - $margin * 3 - $this->wLegend);

        if($color == null)
            $color=array(155,155,155);
        if ($maxVal == 0) 
        {
            foreach($data as $val)
            {
                if(max($val) > $maxVal)
                {
                    $maxVal = max($val);
                }
            }
        }

        // define the distance between the visual reference lines (the lines which cross the chart's internal area and serve as visual reference for the column's heights)
        $valIndRepere = ceil($maxVal / $nbDiv);

        // adjust the maximum value to be plotted (recalculate through the newly calculated distance between the visual reference lines)
        $maxVal = $valIndRepere * $nbDiv;

        // define the distance between the visual reference lines (in milimeters)
        $hRepere = floor($hDiag / $nbDiv);

        // adjust the chart HEIGHT
        $hDiag = $hRepere * $nbDiv;

        // determine the height unit (milimiters/data unit)
        $unit = $hDiag / $maxVal;

        // determine the bar's thickness
        $lBar = floor($lDiag / ($this->NbVal + 1));
        $lDiag = $lBar * ($this->NbVal + 1);
        $eColumn = floor($lBar * 80 / 100);

        // draw the chart border
        $this->SetLineWidth(0.2);
        $this->Rect($XDiag, $YDiag, $lDiag, $hDiag);

        $this->SetFont('Courier', '', 10);
        $this->SetFillColor($color[0],$color[1],$color[2]);
        $i=0;
        foreach($data as $val) 
        {
            //Column
            $yval = $YDiag + $hDiag;
            $xval = $XDiag + ($i + 1) * $lBar - $eColumn/2;
            $lval = floor($eColumn/(count($val)));
            $j=0;
            foreach($val as $v)
            {
                $hval = (int)($v * $unit);
                $this->SetFillColor($colors[$j][0], $colors[$j][1], $colors[$j][2]);
                $this->Rect($xval+($lval*$j), $yval, $lval, -$hval, 'DF');
                $j++;
            }

            //Legend
            $this->SetXY($xval, $yval + $margin);
            $this->Cell($lval, 5, $this->legends[$i],0,0,'C');
            $i++;
        }

        //Scales
        for ($i = 0; $i <= $nbDiv; $i++) 
        {
            $ypos = $YDiag + $hRepere * $i;
            $this->Line($XDiag, $ypos, $XDiag + $lDiag, $ypos);
            $val = ($nbDiv - $i) * $valIndRepere;
            $ypos = $YDiag + $hRepere * $i;
            $xpos = $XDiag - $margin - $this->GetStringWidth($val);
            $this->Text($xpos, $ypos, $val);
        }
    }

    function SetLegends($data, $format)
    {
        $this->legends=array();
        $this->wLegend=0;
        $this->NbVal=count($data);
    }
}


?>