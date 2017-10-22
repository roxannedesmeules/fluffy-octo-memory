export class BreadcrumbItems {
    public name = "";
    public link = "";
    public isActive = false;

    constructor ( model: any = null ) {
        if (model) {
            this.name = model.name;
            this.link = model.link;
            this.isActive = model.isActive;
        }
    }
}
