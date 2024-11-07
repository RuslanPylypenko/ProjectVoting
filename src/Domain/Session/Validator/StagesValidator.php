<?php

namespace App\Domain\Session\Validator;

use App\Domain\Session\Entity\Stage;
use App\Domain\Session\Enum\StageName;

class StagesValidator
{
    /**
     * @param Stage[] $stages
     */
    public function validate(array $stages): void
    {
        $indexedStages = [];
        foreach ($stages as $stage) {
            $indexedStages[$stage->getName()->value] = $stage;
        }

        if (!empty($diff = array_diff(StageName::VALUES, array_keys($indexedStages)))) {
            throw new \DomainException(sprintf('The following stages are not exists: %s. Valid stages are: %s.', implode(', ', $diff), implode(', ', StageName::VALUES)));
        }

        $previousStage = null;
        foreach (StageName::VALUES as $stageValue) {
            if (!array_key_exists($stageValue, $indexedStages)) {
                throw new \DomainException(sprintf('Session stage "%s" not found in indexed stages.', $stageValue));
            }

            $stage = $indexedStages[$stageValue];

            // Check if the previous stage ended before the current stage starts
            if ($previousStage && $previousStage->getEndDate() >= $stage->getStartDate()) {
                throw new \DomainException(sprintf('The previous session stage "%s" must end before the start of the current stage "%s".', $previousStage->getName()->value, $stage->getName()->value));
            }

            // Validate that the start date is before the end date
            if ($stage->getStartDate() >= $stage->getEndDate()) {
                throw new \DomainException(sprintf('Invalid date range for stage "%s": start date "%s" must be earlier than end date "%s".', $stage->getName()->value, $stage->getStartDate()->format('Y-m-d'), $stage->getEndDate()->format('Y-m-d')));
            }

            // Update the previous stage
            $previousStage = $stage;
        }
    }
}
