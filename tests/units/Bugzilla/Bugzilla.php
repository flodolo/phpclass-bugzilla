<?php
namespace tests\units\Bugzilla;

use atoum;
use Bugzilla\Bugzilla as _Bugzilla;

require_once __DIR__ . '/../bootstrap.php';

class Bugzilla extends atoum\test
{
    public function getBugzillaLocaleFieldDP()
    {
        return [
            ['it', 'all', false, 'it / Italian'],
            ['fr', '', false, 'fr / French'],
            ['de', 'www', false, 'de / German'],
            ['es-ES', 'desktop', false, 'es-ES / Spanish'],
            ['es-ES', 'www', false, 'es-ES / Spanish (Spain)'],
            ['ab-CD', '', false, 'ab-CD'],
        ];
    }

    /**
     * @dataProvider getBugzillaLocaleFieldDP
     */
    public function testGetBugzillaLocaleField($a, $b, $c, $d)
    {
        $obj = new _Bugzilla();
        $this
            ->string($obj->getBugzillaLocaleField($a, $b, $c))
                ->isEqualTo($d);
    }

    public function testGetBugsFromCSV()
    {
        $obj = new _Bugzilla();
        $csv_data_short = $obj->getBugsFromCSV(TEST_FILES . 'bugzilla.csv');
        $csv_data_full = $obj->getBugsFromCSV(TEST_FILES . 'bugzilla.csv', true);

        // Check number of read bugs (2)
        $this
            ->integer(count($csv_data_short))
                ->isEqualTo(2);

        // Check data for the first bug
        $this
            ->string($csv_data_short[505881])
                ->isEqualTo('Create an es-* download page');

        // Check number of bugs in the full output
        $this
            ->integer(count($csv_data_full))
                ->isEqualTo(2);

        // Check number of fields in the full output
        $this
            ->integer(count(array_keys($csv_data_full[0])))
                ->isEqualTo(8);

        // Check value of one extra field
        $this
            ->string($csv_data_full[0]['product'])
                ->isEqualTo('www.mozilla.org');
    }
}
