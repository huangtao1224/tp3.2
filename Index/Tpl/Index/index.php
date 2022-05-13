<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="js/laydate/laydate.js"></script>
	<style type="text/css">
		input{border:1px solid #ccc;outline: none;}
		.flex{display: flex;align-items: center;margin-bottom: 5px;}
		input[type='text']{width:200px;height: 30px;line-height: 30px;}
		input[type='image']{width:20px;margin-left: 5px;height: 20px;border:none;}
	</style>
</head>
<body>
	<div class="flex">
		<input type="text" class="month" />
		<input type="image" src="images/rili.png" class="layer-input" value="" />
	</div>
	<div class="flex">
		<input type="text" class="month" />
		<input type="image" src="images/rili.png" class="layer-input" value="" />
	</div>
	<div class="flex">
		<input type="text" class="month" />
		<input type="image" src="images/rili.png" class="layer-input" value="" />
	</div>
	<script type="text/javascript">
		//年月选择器
		var m="";
		lay('.layer-input').each(function(cur){
		    laydate.render({
		        elem: this ,
		        trigger: 'click',
		        type:'month',
		        range:true,
		        done: function(value, date, endDate){
		        	var lens=endDate.year-date.year;
		        	if(date.year==endDate.year){
					    var sm=date.month;var em=endDate.month;
					    m=date.year+'.';
					    for(var i=sm; i<=em; i++){
					    	if(i<10){
					    		i="0"+i;
					    	}
					    	m+=i+',';
					    }
					    m=m.slice(0,(m.length-1));
					    $('.month').eq(cur).val(m);
					}else{
						for(var i=0; i<=lens; i++){
							if(i==0){
								m=date.year+'.';
								for(var j=date.month; j<=12; j++){
									if(j<10){
							    		j="0"+j;
							    	}
							    	if(j==12){
							    		m+=j+' | ';
							    	}else{
							    		m+=j+',';
							    	}
							    	
								}
							}else if(i<lens){
								m+=(date.year+i)+'.';
								for(var j=1; j<=12; j++){
									if(j<10){
							    		j="0"+j;
							    	}
							    	if(j==12){
							    		m+=j+' | ';
							    	}else{
							    		m+=j+',';
							    	}
								}
							}else{
								m+=(date.year+i)+'.';
								for(var j=1; j<=endDate.month; j++){
									if(j<10){
							    		j="0"+j;
							    	}
							    	m+=j+',';
								}
							}
						}
						m=m.slice(0,(m.length-1));
					    $('.month').eq(cur).val(m);
					}
				}
		    });
		});
	</script>
</body>
</html>

