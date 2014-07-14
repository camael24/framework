<?php
namespace Sohoa\Bin\Command\Sqlite {

    use Hoa\Console\Chrome\Text;
    use Hoa\Core\Core;
    use Hoa\Stringbuffer\ReadWrite;
    use Sohoa\Framework\Framework;
    use Sohoa\Framework\Router;
    use Sohoa\Framework\View\Greut;

    class Read extends \Hoa\Console\Dispatcher\Kit
    {

        protected $options = array(
            array('file', \Hoa\Console\GetOption::REQUIRED_ARGUMENT, 'f'),
            array('help', \Hoa\Console\GetOption::NO_ARGUMENT, 'h'),
            array('help', \Hoa\Console\GetOption::NO_ARGUMENT, '?')
        );

        /**
         * The entry method.
         *
         * @access  public
         * @return  int
         */
        public function main()
        {

            $file = null;

            while (false !== $c = $this->getOption($v)) {
                switch ($c) {
                    case 'f':
                        $file = $v;
                        break;
                    case 'h':
                    case '?':
                        return $this->usage();
                        break;
                }
            }

            if ($file === null) {
                return $this->usage();
            }

            if (file_exists($file) === false) {
                throw new \Exception("File not found");
            }

            $p = array(
                'connection.list.default.dal'   => \Hoa\Database\Dal::PDO,
                'connection.list.default.dsn'   => 'sqlite:'.$file,
                'connection.autoload'           => 'default'
            );

            \Hoa\Database\Dal::initializeParameters($p);

            echo \Hoa\Console\Chrome\Text::colorize('READ : '.$file, 'fg(yellow)') ."\n";
            echo \Hoa\Console\Chrome\Text::colorize('   in  '.resolve($file), 'fg(yellow)') ."\n";

            $sql    = "SELECT name FROM sqlite_master WHERE type='table';";
            $layer  = \Hoa\Database\Dal::getInstance('default');
            $tables = $layer->query($sql)->fetchAll();
            $result = array();

            foreach ($tables as $table) {
                $table    = $table['name'];
                $result[] = $this->rendTable($table, $layer);
            }

        }

        protected function rendTable($table, $layer)
        {
            echo \Hoa\Console\Chrome\Text::colorize('TABLE : '.$table, 'fg(green)') ."\n";
            $sql    = 'PRAGMA table_info(`'.$table.'`)';
            $data   = $layer->query($sql)->fetchAll();
            $elment = array();

            foreach ($data as $col) {
                $element[] = $col['name'];
            }

            $element = array($element);
            $sql     = 'SELECT * FROM `'.$table.'`';
            $data    = $layer->query($sql)->fetchAll();

            foreach ($data as $col) {
                $element[] = $this->_val(array_values($col));
                //$element[] = $this->_val(array_values($col));
            }

            echo \Hoa\Console\Chrome\Text::columnize($element);

            return array();
        }

        protected function _val(Array $values)
        {
            foreach ($values as $key => $value) {
                if (strlen($value) > 10) {
                    $values[$key] = substr($value, 0, 10).'[...]';
                }
            }

            return $values;
        }

        /**
         * The command usage.
         *
         * @access  public
         * @return  int
         */
        public function usage()
        {
            echo \Hoa\Console\Chrome\Text::colorize('Usage:', 'fg(yellow)') . "\n";
            echo '   Sqlite:Read ' . "\n\n";

            echo \Hoa\Console\Chrome\Text::colorize('Options:', 'fg(yellow)'), "\n";
            echo $this->makeUsageOptionsList(array(
                'help' => 'This help.',
                'file' => 'Use this file in sqlite reader'
            ));

            return;
        }
    }
}

__halt_compiler();
Read sqlite database in CLI
