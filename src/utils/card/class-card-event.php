<?php
class CardEvent extends Card {

	public function getEvent() {
		return EventModel::getNextEvent();
	}

	public function getEventsOverviewUrl() {
		return EVENTS()->getOverviewUrl();
	}

	public function getNavButtons() {
		return $this->filterButtons( [
			[
				'label' => __( 'Agenda', 'judo-losser' ),
				'url' => $this->getEventsOverviewUrl(),
				'type' => 'all'
			]
		] );
	}
}