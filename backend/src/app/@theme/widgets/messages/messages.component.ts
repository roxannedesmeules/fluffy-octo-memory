import { Component, OnInit } from "@angular/core";
import { ErrorResponse } from "@core/data/error-response.model";
import { Communication, CommunicationService } from "@core/data/communication";
import { MessagesService } from "./messages.service";

@Component({
	selector    : "app-widgets-messages",
	templateUrl : "./messages.component.html",
	styleUrls   : [ "./messages.component.scss" ],
})
export class MessagesComponent implements OnInit {

	public count: number | null  = 0;
	public list: Communication[] = [];

	constructor (private service: CommunicationService, private messageWidget: MessagesService) {
	}

	ngOnInit () {
		this._unreadCount();
		this._getMostRecent();

		this.messageWidget.getSubject().subscribe((result: boolean) => {
			this._unreadCount();
			this._getMostRecent();
		});
	}

	/**
	 * Called during the initialization of the component, this method will get the 3 most recent unread messages.
	 *
	 * @private
	 */
	private _getMostRecent () {
		this.service.filters.setPagination({ currentPage : 0, perPage : 3 });
		this.service.filters.set("viewed", 0);

		this.service.findAll()
				.subscribe(
						(result: Communication[]) => {
							this.list = result;
						},
						(err: ErrorResponse) => {
							console.log(err);
						}
				);
	}

	/**
	 * Called during the initialization of the component, this method will get the number of unread messages.
	 *
	 * @private
	 */
	private _unreadCount () {
		this.service.filters.set("viewed", 0);

		this.service.count()
				.subscribe(
						(result: any) => {
							this.count = result.count;
						},
						(err: ErrorResponse) => {
							console.log(err);
						}
				);
	}
}
