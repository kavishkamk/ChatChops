<?php
    class R_ScriptExecute{

        private $rpath = 'C:\\"Program Files"\\R\\R-4.0.3\\bin\\Rscript.exe C:\\xampp\\htdocs\\chatchops\\source\\';

        public function rExecutive($scriptPath){
            $val = $this->rpath.$scriptPath;
            exec($val);
        }

    }