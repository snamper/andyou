<!DOCTYPE html>
<html>
<head>
	<meta charset="GBK" />
	<title>��ֵ��ӡ</title>
    <style>
        
    </style>
</head>
<body>
    <?php
    error_reporting(0);
    ?>
    <div style="text-align:center;padding:50px 0;margin:40px auto;width:500px;border:1px solid #cccccc;">
        СƱ��ӡ��...
        <br/><br/>
        <a href="?c=Checkout">���ؼ�������</a>
    </div>
<script src="js/jquery-1.7.2.min.js" type="text/javascript"></script>
<script src="js/LodopFuncs.js" type="text/javascript"></script>

<script language="javascript" type="text/javascript"> 
   var LODOP; //����Ϊȫ�ֱ���
   var iTop = 0;
   var pageWidth = "48mm";
   var txtLineHeight = 15;
	function MyPreview() {	
		LODOP=getLodop();  
		LODOP.PRINT_INIT("��ӡ");
		createContent(0);
        var pnum = $("#pnum").val();
        if(pnum != 1){
            iTop += 50;
            createContent(1);
        }
		LODOP.SET_PRINT_PAGESIZE(3,580,45,"");//����3��ʾ�����ӡ��ֽ�ߡ������ݵĸ߶ȡ���1385��ʾֽ��138.5mm��45��ʾҳ�׿հ�4.5mm
		//LODOP.PREVIEW();	
		LODOP.PRINT();	
	};
    
    
    
	function createContent(iii){	
        
        //����
		LODOP.ADD_PRINT_TEXT(iTop,0,pageWidth,20,"<?=$sysName?>");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",9);
		LODOP.SET_PRINT_STYLEA(0,"Bold",1);
		LODOP.SET_PRINT_STYLEA(0,"Alignment",2);
        //��ӭ��
        iTop += 20;
		LODOP.ADD_PRINT_TEXT(iTop,0,pageWidth,txtLineHeight,"<?=$sysCfg['PrintSubTitle']["value"] ?>");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
		LODOP.SET_PRINT_STYLEA(0,"Bold",0);
		LODOP.SET_PRINT_STYLEA(0,"Alignment",2);
        
        iTop += 10;
        
        
        //��Ա��Ϣ
        <?php
        
        if($memberInfo){
            $txtArr = array(
                "��Ա���ţ�".$memberInfo["cardno"],
                "��Ա���ͣ�".$memberInfo["cateName"],
                "��ֵ���ţ�"."No.".$bno,
                "��ֵ��".$money,
                "��ǰ��".$nowBalance,
                "�� �� Ա��".$staffName,
                "����ʱ�䣺".date("Y-m-d H:i",SYSTEM_TIME),
            );
            
            $i = 4;
            foreach ($txtArr as $txt){
                echo "iTop += txtLineHeight;
                LODOP.ADD_PRINT_TEXT(iTop,0,pageWidth,txtLineHeight,'{$txt}');LODOP.SET_PRINT_STYLEA(0,'FontSize',8);
                ";
                
            }
            
        }
        ?>
               
        iTop += 10;
        
        if(iii == 1){
            iTop += txtLineHeight;
            LODOP.ADD_PRINT_TEXT(iTop,0,pageWidth,txtLineHeight,'<��һ��>�̻�����');LODOP.SET_PRINT_STYLEA(0,'FontSize',8);
            iTop += txtLineHeight;
            LODOP.ADD_PRINT_TEXT(iTop,0,pageWidth,txtLineHeight,'��˿����·�ǩ�֣�');LODOP.SET_PRINT_STYLEA(0,'FontSize',8);
            
            iTop += txtLineHeight*3;
        }else{
            iTop += txtLineHeight;
            LODOP.ADD_PRINT_TEXT(iTop,0,pageWidth,txtLineHeight,'<�ڶ���>�˿�����');LODOP.SET_PRINT_STYLEA(0,'FontSize',8);
            
        }
        
        //�ײ�
        iTop += txtLineHeight;
		LODOP.ADD_PRINT_TEXT(iTop,0,pageWidth,txtLineHeight,"<?=$sysCfg['PrintEndTitle']["value"] ?>");
		LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
		LODOP.SET_PRINT_STYLEA(0,"Alignment",2);
        
        iTop += txtLineHeight;
		LODOP.ADD_PRINT_TEXT(iTop,0,pageWidth,txtLineHeight,"лл���٣����ǽ��߳�Ϊ������");
		LODOP.SET_PRINT_STYLEA(0,'FontSize',8);
		LODOP.SET_PRINT_STYLEA(0,"Alignment",2);
        
        
	};	
    MyPreview();
    setTimeout(function(){
         window.location.href = "?c=Checkout";
    },2000);
</script> 
    <script>
        //printDiv
    var print = function(){
//        var pnum = $("#pnum").val();
//        if(pnum != 1){
//            $("#printDiv").append($("#printDiv").html());
//        }
        $("#btnPrint").val("��ӡ��!...");
        //$("#printDiv").jqprint();
        MyPreview();
    }
    
    var goback = function(){
        window.location.href = "?c=Checkout";
    }
    </script>
</body></html> 