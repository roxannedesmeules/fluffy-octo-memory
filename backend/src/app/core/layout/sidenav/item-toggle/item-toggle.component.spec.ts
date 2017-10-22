import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ItemToggleComponent } from './item-toggle.component';

describe('ItemToggleComponent', () => {
  let component: ItemToggleComponent;
  let fixture: ComponentFixture<ItemToggleComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ItemToggleComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ItemToggleComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
