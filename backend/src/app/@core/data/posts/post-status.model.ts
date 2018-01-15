export const STATUS_MAPPING = {
	draft       : [ "unpublished" ],
	unpublished : [ "published" ],
	published   : [ "archived" ],
	archived    : [ "unpublished" ],
};

export class PostStatus {
	public id: number;
	public name: string;

	constructor ( model: any ) {
		if (!model) { return; }

		this.id   = parseInt(model.id, 10);
		this.name = model.name;
	}

	canMoveToStatus ( status: string ): boolean {
		return (STATUS_MAPPING[ this.name ].indexOf(status) >= 0);
	}
}

export function findStatusById ( statusList: PostStatus[], statusId: number ): PostStatus {
	let status = null;

	statusList.forEach(( val ) => {
		if (val.id === statusId) {
			status = val;
		}
	});

	return status;
}