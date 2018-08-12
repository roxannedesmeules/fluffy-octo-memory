export class Author {
    public id: number;
    public fullname: string;
    public firstname: string;
    public lastname: string;
    public picture: string;

    public biography: string;
    public job_title: string;

    constructor(model: any = null) {
        if (!model) {
            return;
        }

        this.id        = model.id;
        this.fullname  = model.fullname;
        this.firstname = model.firstname;
        this.lastname  = model.lastname;
        this.picture   = model.picture;
        this.biography = model.biography;
        this.job_title = model.job_title;
    }
}
