export class Communication {
	public static NOT_REPLIED = 0;
	public static REPLIED     = 1;

	public static NOT_VIEWED = 0;
	public static VIEWED     = 1;

	public id: number;
	public name: string;
	public email: string;
	public subject: string | null;
	public message: string;
	public is_viewed: number  = Communication.NOT_VIEWED;
	public is_replied: number = Communication.NOT_REPLIED;
	public created_on: string;

	constructor ( model: any = null ) {
		if (!model) {
			return;
		}

		this.id         = model.id;
		this.name       = model.name;
		this.email      = model.email;
		this.subject    = model.subject || null;
		this.message    = model.message;
		this.is_viewed  = model.is_viewed || Communication.NOT_VIEWED;
		this.is_replied = model.is_replied || Communication.NOT_REPLIED;
		this.created_on = model.created_on;
	}

	/**
	 * Return the url used to reply to this message.
	 *
	 * @return {string}
	 */
	public getReplyUrl () {
		let url  = `mailto:${this.email}`;

		if (this.subject !== null) {
			url += "?subject=" + encodeURIComponent(this.subject);
		}

			url += "&body=" + encodeURIComponent('\n----------\n' + this.message);

		return url;
	}

	/**
	 * Helper method to know if the message wasn't replied.
	 *
	 * @return {boolean}
	 */
	public isNotReplied () {
		return (this.is_replied === Communication.NOT_REPLIED);
	}

	/**
	 * Helper method to know if the message was replied.
	 *
	 * @return {boolean}
	 */
	public isReplied () {
		return (this.is_replied === Communication.REPLIED);
	}

	/**
	 * Helper method to know if the message wasn't viewed.
	 *
	 * @return {boolean}
	 */
	public isNotViewed () {
		return (this.is_viewed === Communication.NOT_VIEWED);
	}

	/**
	 * Helper method to know if the message was viewed.
	 *
	 * @return {boolean}
	 */
	public isViewed () {
		return (this.is_viewed === Communication.VIEWED);
	}

}