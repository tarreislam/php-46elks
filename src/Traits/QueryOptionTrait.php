<?php


namespace Tarre\Php46Elks\Traits;


trait QueryOptionTrait
{
    protected $queryOptions = [];

    /**
     * @param string $option
     * @param $value
     * @return $this
     */
    public function setOption(string $option, $value): self
    {
        $this->queryOptions[$option] = $value;

        return $this;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options): self
    {
        foreach ($options as $option => $value) {
            $this->setOption($option, $value);
        }
        return $this;
    }

    /**
     * $flush bool if true, the "options" array will be reset
     * @return array
     */
    public function getOptions($flush = false): array
    {
        $queryOptions = $this->queryOptions;
        if ($flush) {
            $this->queryOptions = [];
        }
        return $queryOptions;
    }

    /**
     * @param string $option
     * @return bool
     */
    public function hasOption(string $option): bool
    {
        return (bool)isset($this->queryOptions[$option]);
    }

    /**
     * @param string $option
     * @return $this
     */
    public function removeOption(string $option)
    {
        if ($this->hasOption($option)) {
            unset($this->queryOptions[$option]);
        }

        return $this;
    }
}
