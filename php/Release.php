<?php
namespace Rarst\ReleaseBelt;

use Symfony\Component\Finder\SplFileInfo;

class Release
{
    const SEPARATORS = '.-_';

    // From https://github.com/composer/semver/blob/master/src/VersionParser.php
    const MODIFIER_REGEX = '[._-]?(?:(stable|beta|b|RC|alpha|a|patch|pl|p)((?:[.-]?\d+)*+)?)?([.-]?dev)?';

    const VERSION_REGEX = '/(?P<package>.*?)(?P<version>v?(?:\d+\.*){1,4}' . self::MODIFIER_REGEX . ')\.zip/';


    /** @var SplFileInfo $file */
    protected $file;

    public $path;
    public $filename;
    public $type;
    public $vendor;
    public $package;
    public $version;

    public function __construct(SplFileInfo $file)
    {
        $this->file     = $file;
        $this->path     = str_replace('\\', '/', $file->getRelativePath());
        $this->filename = $file->getFilename();
        list( $this->type, $this->vendor ) = explode('/', $this->path);
        $matches       = $this->parseFilename($this->filename);
        $this->package = $matches['package'];
        $this->version = $matches['version'];
    }

    protected function parseFilename($filename)
    {
        preg_match(self::VERSION_REGEX, $filename, $matches);
        $package = trim(rtrim($matches['package'], self::SEPARATORS));
        $version = trim(ltrim($matches['version'], 'v'));

        return compact('package', 'version');
    }
}
