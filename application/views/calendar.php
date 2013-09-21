	<div class="calendar-wrap">
	<div class="calendar-header">
		<nav>
			<span id="calendar-prev" class="calendar-prev"><a href="<?= URL::site("/events/calendar/".$prevdate)?>">◂</a></span>
			<span id="calendar-next" class="calendar-next"><a href="<?= URL::site("/events/calendar/".$nextdate)?>">▸</a></span>
		</nav>
		<h2 id="calendar-month" class="calendar-month"><?= $calendar['monthname']; ?></h2>
		<h4 id="calendar-year" class="calendar-year"><?= $calendar['year']; ?></h4>
	</div>
	<div id="calendar" class="calendar-container">
		<table class="calendar-body">
			<tbody>
				<tr class="calendar-head full">
					<th>Понедельник</th>
					<th>Вторник</th>
					<th>Среда</th>
					<th>Четверг</th>
					<th>Пятница</th>
					<th>Субота</th>
					<th>Воскресенье</th>
				</tr>
				<tr class="calendar-head short">
					<th>Пн</th>
					<th>Вт</th>
					<th>Ср</th>
					<th>Чт</th>
					<th>Пт</th>
					<th>Сб</th>
					<th>Вс</th>
				</tr>
<?php 
$day=1;
for($i = 0; $i < 6; $i++) // Внешний цикл для недель 6 с неполыми
{ 
	echo '<tr>';
	for($j = 1; $j < 8; $j++)	// Внутренний цикл для дней недели
	{
		if(($calendar['skip'] > 0)or($day > $calendar['daysInMonth'])) // выводим пустые ячейки до 1 го дня ип после полного количства дней
		{
			echo '<td class="calendar-empty">&nbsp;</td>';
			$calendar['skip']--;
		}
		else
		{
			if($j == 7)	// если воскресенье то омечаем выходной
				echo '<td class="holiday"><div class="day-number">'.$day.'</div></td>'; 

			else
			{	// в противном случае просто выводим день в ячейке
				if ((date('j')==$day)&&(date('m')==$calendar['month'])&&(date('Y')==$calendar['year'])){//проверяем на текущий день
					echo '<td class="today">';}
				else 
				{
					echo '<td class="day">';
				}

				if(isset($events[$day]))
				{

					echo '<div class="day-number">'.$day.'</div>';

					foreach($events[$day] as $event) {
							echo '<a href="'.URL::site("event/".$event["id"]).'" class="event-link">'.$event["title"].'</a><br>';
					}
						echo '</td>';
				}
				else
				{
					echo '<div class="day-number">'.$day.'</div></td>';
				}
			}
				$day++; // увеличиваем $day
		}
			
	
	}	// закрываем внутренний цикл
	echo '</tr>';
	if ($day > $calendar['daysInMonth']) break;//прерываем цикл, чтобы не выводить пустую 6ю строку

} // закрываем внешний цикл


	?>
			</tbody>
		</table>
	</div>
</div>