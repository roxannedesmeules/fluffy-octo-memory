export class PostCover {
    public url: string;
    public alt: string;

    constructor(model: any = null) {
        if (!model) {
            return;
        }

        this.url = model.url;
        this.alt = model.alt;
    }
}
