import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CustomAdminPageComponent } from './custom-admin-page.component';

describe('CustomAdminPageComponent', () => {
  let component: CustomAdminPageComponent;
  let fixture: ComponentFixture<CustomAdminPageComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CustomAdminPageComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CustomAdminPageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
