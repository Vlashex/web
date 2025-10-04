import React from "react";
import {
  Layout,
  Menu,
  Table,
  Form,
  Input,
  Button,
  Radio,
  Select,
  DatePicker,
  Checkbox,
  Card,
} from "antd";
import "./App.css";

const { Header, Footer, Content } = Layout;
const { Option } = Select;
const { TextArea } = Input;

const App = () => {
  const [form] = Form.useForm();

  // Данные для таблицы
  const tableData = [
    {
      key: "1",
      id: "1",
      firstName: "Иван",
      lastName: "Иванов",
      note: "Студент",
    },
    {
      key: "2",
      id: "2",
      firstName: "Анна",
      lastName: "Сидорова",
      note: "Преподаватель",
    },
    {
      key: "3",
      id: "3",
      firstName: "Мария",
      lastName: "Кузнецова",
      note: "Стажёр",
    },
    {
      key: "4",
      id: "4",
      firstName: "Олег",
      lastName: "Смирнов",
      note: "Ассистент",
    },
    {
      key: "5",
      id: "5",
      firstName: "Дарья",
      lastName: "Фролова",
      note: "Администратор",
    },
    {
      key: "6",
      id: "6",
      firstName: "Пётр",
      lastName: "Петров",
      note: "Студент",
    },
  ];

  const tableColumns = [
    {
      title: "№",
      dataIndex: "id",
      key: "id",
      rowScope: "row",
      onCell: (_, index) => ({
        rowSpan: index === 0 ? 2 : index === 1 ? 0 : 1,
      }),
    },
    {
      title: "ФИО",
      children: [
        {
          title: "Имя",
          dataIndex: "firstName",
          key: "firstName",
        },
        {
          title: "Фамилия",
          dataIndex: "lastName",
          key: "lastName",
        },
      ],
    },
    {
      title: "Примечание",
      dataIndex: "note",
      key: "note",
      onCell: (_, index) => ({
        rowSpan: index === 0 ? 2 : index === 1 ? 0 : 1,
      }),
    },
  ];

  const onFinish = (values) => {
    console.log("Form values:", values);
  };

  return (
    <Layout className="layout">
      <Header className="header">
        <div className="container header-inner">
          <a href="/" className="logo" aria-label="На главную">
            <img
              src="https://kubsu.ru/sites/default/files/logo.png"
              alt="Логотип КубГУ"
              width="60"
            />
          </a>
          <h1 className="site-title">Учебный сайт</h1>
          <nav aria-label="Основное меню">
            <Menu mode="horizontal" className="menu" theme="dark">
              <Menu.Item key="1">
                <a href="#">Меню 1</a>
              </Menu.Item>
              <Menu.Item key="2">
                <a href="#">Меню 2</a>
              </Menu.Item>
              <Menu.Item key="3">
                <a href="#">Меню 3</a>
              </Menu.Item>
            </Menu>
          </nav>
        </div>
      </Header>

      <Content className="main-content">
        <div className="container">
          <section aria-labelledby="links-heading" className="section-card">
            <Card
              title={
                <h2 id="links-heading" style={{ margin: 0 }}>
                  Маркированный список ссылок
                </h2>
              }
              className="card"
            >
              <ul className="links-list">
                <li>
                  <a href="http://kubsu.ru/">1) Абсолютная ссылка (http)</a>
                </li>
                <li>
                  <a href="https://kubsu.ru/">2) Абсолютная ссылка (https)</a>
                </li>
                <li>
                  3)
                  <a href="https://kubsu.ru/">
                    <img
                      src="https://kubsu.ru/sites/default/files/logo.png"
                      alt="Ссылка-изображение"
                      width="100"
                    />
                  </a>
                </li>
                <li>
                  <a href="/about">
                    4) Сокращённая ссылка на внутреннюю страницу
                  </a>
                </li>
                <li>
                  <a href="/">5) Сокращённая ссылка на главную</a>
                </li>
                <li>
                  <a href="#fragment">6) Ссылка на фрагмент текущей страницы</a>
                </li>
                <li>
                  <a href="https://example.com/page?x=1&amp;y=2&amp;z=3">
                    7) Ссылка с тремя параметрами
                  </a>
                </li>
                <li>
                  <a href="https://example.com/page?id=15">
                    8) Ссылка с параметром id
                  </a>
                </li>
                <li>
                  <a href="page.html">
                    9) Относительная на страницу в текущем каталоге
                  </a>
                </li>
                <li>
                  <a href="about/page.html">
                    10) Относительная на страницу в каталоге about
                  </a>
                </li>
                <li>
                  <a href="../page.html">
                    11) Относительная на страницу уровнем выше
                  </a>
                </li>
                <li>
                  <a href="../../page.html">
                    12) Относительная на страницу двумя уровнями выше
                  </a>
                </li>
                <li>
                  13) Контекстная ссылка: Мы учимся в
                  <a href="https://kubsu.ru/">КубГУ</a>.
                </li>
                <li>
                  <a href="https://ru.wikipedia.org/wiki/HTML#История">
                    14) Ссылка на фрагмент стороннего сайта
                  </a>
                </li>
                <li>
                  15) Ссылки из областей картинки:
                  <br />
                  <img
                    src="https://via.placeholder.com/300x400"
                    alt=""
                    useMap="#image-map"
                    className="map-image"
                  />
                  <map name="image-map">
                    <area
                      href="https://www.wikipedia.org/"
                      coords="29,52,123,369"
                      shape="rect"
                      alt="Wikipedia"
                    />
                    <area
                      href="https://www.wiktionary.org/"
                      coords="304,217,125"
                      shape="circle"
                      alt="Wiktionary"
                    />
                  </map>
                </li>
                <li>
                  <a href="#">16) Ссылка с пустым href</a>
                </li>
                <li>
                  <span>17) Ссылка без href</span>
                </li>
                <li>
                  <a href="https://example.com/" rel="nofollow">
                    18) Запрещён переход поисковикам
                  </a>
                </li>
                <li>
                  <a href="https://example.com/" rel="nofollow">
                    19) Запрещено для индексации
                  </a>
                </li>
                <li>
                  20) Нумерованный список ссылок с title:
                  <ol>
                    <li>
                      <a href="https://example.com/" title="Описание ссылки 1">
                        Ссылка 1
                      </a>
                    </li>
                    <li>
                      <a href="https://example.com/" title="Описание ссылки 2">
                        Ссылка 2
                      </a>
                    </li>
                    <li>
                      <a href="https://example.com/" title="Описание ссылки 3">
                        Ссылка 3
                      </a>
                    </li>
                  </ol>
                </li>
                <li>
                  <a href="ftp://user:password@ftp.example.com/file.txt">
                    21) Ссылка на файл на FTP
                  </a>
                </li>
              </ul>
            </Card>
          </section>

          <section aria-labelledby="table-heading" className="section-card">
            <Card
              title={
                <h2 id="table-heading" style={{ margin: 0 }}>
                  Таблица
                </h2>
              }
              className="card"
            >
              <Table
                dataSource={tableData}
                columns={tableColumns}
                pagination={false}
                bordered
                size="middle"
                className="custom-table"
              />
            </Card>
          </section>

          <section aria-labelledby="form-heading" className="section-card">
            <Card
              title={
                <h2 id="form-heading" style={{ margin: 0 }}>
                  Форма
                </h2>
              }
              className="card"
            >
              <Form
                form={form}
                onFinish={onFinish}
                layout="vertical"
                className="custom-form"
              >
                <Form.Item
                  label="ФИО"
                  name="fio"
                  rules={[
                    { required: true, message: "Пожалуйста, введите ФИО" },
                  ]}
                >
                  <Input placeholder="Иванов Иван Иванович" prefix={<></>} />
                </Form.Item>

                <Form.Item
                  label="Телефон"
                  name="phone"
                  rules={[
                    { required: true, message: "Пожалуйста, введите телефон" },
                  ]}
                >
                  <Input placeholder="+7 (999) 123-45-67" prefix={<></>} />
                </Form.Item>

                <Form.Item
                  label="E-mail"
                  name="email"
                  rules={[
                    { required: true, message: "Пожалуйста, введите email" },
                    { type: "email", message: "Введите корректный email" },
                  ]}
                >
                  <Input placeholder="example@mail.ru" prefix={<></>} />
                </Form.Item>

                <Form.Item
                  label="Дата рождения"
                  name="birthdate"
                  rules={[
                    {
                      required: true,
                      message: "Пожалуйста, выберите дату рождения",
                    },
                  ]}
                >
                  <DatePicker
                    style={{ width: "100%" }}
                    placeholder="Выберите дату"
                    prefix={<></>}
                  />
                </Form.Item>

                <Form.Item
                  label="Пол"
                  name="gender"
                  rules={[
                    { required: true, message: "Пожалуйста, выберите пол" },
                  ]}
                >
                  <Radio.Group>
                    <Radio value="male">Мужской</Radio>
                    <Radio value="female">Женский</Radio>
                  </Radio.Group>
                </Form.Item>

                <Form.Item
                  label="Любимый язык программирования"
                  name="languages"
                  rules={[
                    { required: true, message: "Пожалуйста, выберите языки" },
                  ]}
                >
                  <Select
                    mode="multiple"
                    size="large"
                    placeholder="Выберите языки"
                  >
                    <Option value="Pascal">Pascal</Option>
                    <Option value="C">C</Option>
                    <Option value="C++">C++</Option>
                    <Option value="JavaScript">JavaScript</Option>
                    <Option value="PHP">PHP</Option>
                    <Option value="Python">Python</Option>
                    <Option value="Java">Java</Option>
                    <Option value="Haskell">Haskell</Option>
                    <Option value="Clojure">Clojure</Option>
                    <Option value="Prolog">Prolog</Option>
                    <Option value="Scala">Scala</Option>
                  </Select>
                </Form.Item>

                <Form.Item
                  label="Биография"
                  name="bio"
                  rules={[
                    {
                      required: true,
                      message: "Пожалуйста, заполните биографию",
                    },
                  ]}
                >
                  <TextArea rows={5} placeholder="Расскажите о себе..." />
                </Form.Item>

                <Form.Item
                  name="contract"
                  valuePropName="checked"
                  rules={[
                    {
                      required: true,
                      message: "Необходимо согласие с контрактом",
                    },
                  ]}
                >
                  <Checkbox>С контрактом ознакомлен(а)</Checkbox>
                </Form.Item>

                <Form.Item>
                  <Button
                    type="primary"
                    htmlType="submit"
                    icon={<></>}
                    size="large"
                  >
                    Сохранить
                  </Button>
                </Form.Item>
              </Form>
            </Card>
          </section>
        </div>
      </Content>

      <Footer className="footer">
        <div className="container">
          <p>&copy; 2025 Учебный сайт. Все права защищены.</p>
        </div>
      </Footer>
    </Layout>
  );
};

export default App;
