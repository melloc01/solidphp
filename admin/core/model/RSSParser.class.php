 <?php

class RSSParser {

   var $insideitem = false;
   var $tag = "";
   var $title = "";
   var $description = "";
   var $link = "";
   var $array_news = array();

   function startElement($parser, $tagName, $attrs) {
       if ($this->insideitem) {
           $this->tag = $tagName;
       } elseif ($tagName == "ITEM") {
           $this->insideitem = true;
       }
   }

   function endElement($parser, $tagName){
       if ($tagName == "ITEM") {
           
           /*print "
           <div>" . $this->title . "</div>
           <div class=\"text\">" . $this->description . "<a href=\"" . $this->link . "\" target=\"_blank\">Read the Rest of This Story</a></div><br />";
           */
          $this->array_news[] = array( 'title' => $this->title, 'description' => $this->description, 'link' => $this->link ); 
            
           $this->title = "";
           $this->description = "";
           $this->link = "";
           $this->insideitem = false;

       }
   }

   public function getNewsArray()
   {
     return $this->array_news;
   }

   function characterData($parser, $data) {
       if ($this->insideitem) {
           switch ($this->tag) {
               case "TITLE":
               $this->title .= $data;
               break;
               case "DESCRIPTION":
               $this->description .= $data;
               break;
               case "LINK":
               $this->link .= $data;
               break;
           }
       }
   }
}
    
    
    

?> 