export class Lang {
	public id: number;
	public icu: string;
	public name: string;
	public native: string;

	constructor ( model: any = null ) {
		if (!model) { return; }

		this.id     = model.id;
		this.icu    = model.icu;
		this.name   = model.name;
		this.native = model.native;
	}
}
