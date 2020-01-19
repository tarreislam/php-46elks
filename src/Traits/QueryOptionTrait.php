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
     * @return array
     */
    public function getOptions(): array
    {
        return $this->queryOptions;
    }
}
