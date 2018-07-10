import { Component, Input, OnInit } from "@angular/core";
import { Communication, CommunicationService } from "@core/data/communication";
import { ErrorResponse } from "@core/data/error-response.model";
import { MessagesService } from "@theme/widgets";

@Component({
	selector    : "app-communication-detail",
	templateUrl : "./detail.component.html",
	styleUrls   : [ "./detail.component.scss" ],
})
export class DetailComponent implements OnInit {

	canMarkAsReplied: boolean = false;

	@Input() message: Communication;

	constructor (private service: CommunicationService, private messageWidget: MessagesService) {
	}

	ngOnInit () {
	}

	/**
	 * This method will call the communication service to update the message and mark it as viewed.
	 */
	public markAsViewed () {
		const body = { is_viewed : 1 };

		this.service
			.update(this.message.id, body)
			.subscribe(
				(result: Communication) => {
					this.message = result;

					this.messageWidget.reload();
				},
				(err: ErrorResponse) => { console.log(err); },
			);
	}

	/**
	 * This method will call the communication service to update the message and mark it as replied.
	 */
	public markAsReplied () {
		const body = { is_replied : 1 };

		this.service
			.update(this.message.id, body)
			.subscribe(
					(result: Communication) => {
						this.message = result;

						this.messageWidget.reload();
					},
					(err: ErrorResponse) => { console.log(err); },
			);
	}

	/**
	 * This method will update the canMarkAsReplied flag to indicate that the user is currently replying to the message
	 * and can mark the message as replied. However, this is temporary and will be reset if the list is reloaded.
	 */
	public replying () {
		this.canMarkAsReplied = true;
	}
}
